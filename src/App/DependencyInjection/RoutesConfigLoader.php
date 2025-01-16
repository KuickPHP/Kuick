<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\DependencyInjection;

use FilesystemIterator;
use GlobIterator;
use Kuick\App\AppException;
use Kuick\App\SystemCacheInterface;
use Kuick\Http\Server\Route;
use Psr\Log\LoggerInterface;

/**
 * Route config loader
 */
class RoutesConfigLoader
{
    private const CACHE_KEY = 'kuick-app-routes';
    private const ROUTE_LOCATIONS = [
        '/vendor/kuick/*/config/*.routes.php',
        '/config/*.routes.php',
    ];

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
        foreach (self::ROUTE_LOCATIONS as $routeLocation) {
            $routeIterator = new GlobIterator($projectDir . $routeLocation, FilesystemIterator::KEY_AS_FILENAME);
            foreach ($routeIterator as $routeFile) {
                $includedRoutes = include $routeFile;
                $this->logger->info('Route file added: ' . $routeFile . ', containing: ' . count($includedRoutes) . ' routes');
                $routes = array_merge($routes, $includedRoutes);
            }
        }
        foreach ($routes as $route) {
            if (!($route instanceof Route)) {
                throw new AppException('Route must be an instance of ' . Route::class);
            }
        }
        $this->cache->set(self::CACHE_KEY, $routes);
        $this->logger->notice('Routes parsed (' . count($routes) . '), cache written');
        return $routes;
    }
}
