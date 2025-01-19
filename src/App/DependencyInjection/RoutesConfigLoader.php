<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\DependencyInjection;

use FilesystemIterator;
use GlobIterator;
use Kuick\App\AppException;
use Kuick\App\Config\RouteConfig;
use Kuick\App\SystemCacheInterface;
use Psr\Log\LoggerInterface;

/**
 * Route config loader
 */
class RoutesConfigLoader
{
    private const CACHE_KEY = 'kuick-app-routes';
    private const ROUTES_CONFIG_LOCATION = '/config/*.routes.php';

    public function __construct(
        private SystemCacheInterface $cache,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(string $projectDir): array
    {
        $cachedRoutes = $this->cache->get(self::CACHE_KEY);
        if (null !== $cachedRoutes) {
            $this->logger->info('Routes (' . count($cachedRoutes) . ') loaded from cache');
            return $cachedRoutes;
        }
        $routes = [];
        $routeIterator = new GlobIterator($projectDir . self::ROUTES_CONFIG_LOCATION, FilesystemIterator::KEY_AS_FILENAME);
        foreach ($routeIterator as $routeFile) {
            $includedRoutes = include $routeFile;
            $this->logger->info('Route file added: ' . $routeFile . ', containing: ' . count($includedRoutes) . ' routes');
            $routes = array_merge($routes, $includedRoutes);
        }
        foreach ($routes as $route) {
            if (!($route instanceof RouteConfig)) {
                throw new AppException('Route definition must be an instance of ' . RouteConfig::class);
            }
        }
        $this->cache->set(self::CACHE_KEY, $routes);
        $this->logger->notice('Routes parsed (' . count($routes) . '), cache written');
        return $routes;
    }
}
