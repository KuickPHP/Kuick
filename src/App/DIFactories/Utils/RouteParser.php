<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\DIFactories\Utils;

use FilesystemIterator;
use GlobIterator;
use Kuick\App\AppDIContainerBuilder;
use Kuick\App\KernelAbstract;
use Psr\Log\LoggerInterface;

/**
 *
 */
class RouteParser
{
    private const CACHE_FILE = '/kuick-app-routes.php';
    private const ROUTE_LOCATIONS = [
        '/vendor/kuick/*/config/*.routes.php',
        '/config/*.routes.php',
    ];

    public function __construct(private LoggerInterface $logger)
    {
    }

    public function __invoke($projectDir, $env): array
    {
        $cacheFile = $projectDir . AppDIContainerBuilder::CACHE_PATH . self::CACHE_FILE;
        $cachedRoutes = KernelAbstract::ENV_PROD === $env ? $this->loadFromCache($cacheFile) : false;
        if (false !== $cachedRoutes) {
            $this->logger->info('Routes loaded from cache');
            return $cachedRoutes;
        }
        $routes = $this->parseRoutes($projectDir);
        $this->saveToCache($routes, $cacheFile);
        $this->logger->notice('Routes parsed, cache written');
        return $routes;
    }

    private function parseRoutes($projectDir): array
    {
        $routes = [];
        foreach (self::ROUTE_LOCATIONS as $routeLocation) {
            $routeIterator = new GlobIterator($projectDir . $routeLocation, FilesystemIterator::KEY_AS_FILENAME);
            foreach ($routeIterator as $routeFile) {
                $this->logger->debug('Route file added: ' . $routeFile);
                $routes = array_merge($routes, include $routeFile);
            }
        }
        //validating routes
        //decorating routes with available controller arguments
        foreach ($routes as $routeKey => $route) {
            (new RouteValidator())($route);
            $routes[$routeKey]['arguments'][$route['controller']] = (new ClassInvokeArgumentReflector())($route['controller']);
            if (!isset($route['guards'])) {
                continue;
            }
            foreach ($route['guards'] as $guard) {
                $routes[$routeKey]['arguments'][$guard] = (new ClassInvokeArgumentReflector())($guard);
            }
        }
        return $routes;
    }

    /**
     * @SuppressWarnings(PHPMD.ErrorControlOperator)
     */
    private function loadFromCache($cacheFile): array|false
    {
        $cachedRoutes = @include $cacheFile;
        return $cachedRoutes ?? [];
    }

    private function saveToCache(array $routes, $cacheFile): void
    {
        $cacheDir = dirname($cacheFile);
        !is_dir($cacheDir) && mkdir($cacheDir, 0777, true);
        file_put_contents($cacheFile, sprintf('<?php return %s;', var_export($routes, true)));
    }
}
