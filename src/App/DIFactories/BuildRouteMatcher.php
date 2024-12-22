<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\DIFactories;

use Kuick\App\AppDIContainerBuilder;
use Kuick\App\KernelAbstract;
use Kuick\App\Router\ClassInvokeArgumentReflector;
use Kuick\App\Router\RouteMatcher;
use Kuick\App\Router\RouteValidator;
use Kuick\Cache\FileCache;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 *
 */
class BuildRouteMatcher extends FactoryAbstract
{
    public const CACHE_KEY = 'kuick-app-routematcher-routes';
    public const ROUTE_LOCATIONS = [
        '/vendor/kuick/*/config/*.routes.php',
        '/config/*.routes.php',
    ];

    public function __invoke(): void
    {
        $this->builder->addDefinitions([RouteMatcher::class => function (ContainerInterface $container): RouteMatcher {
            $logger = $container->get(LoggerInterface::class);
            $projectDir = $container->get(AppDIContainerBuilder::PROJECT_DIR_CONFIGURATION_KEY);
            $cache = new FileCache($projectDir . AppDIContainerBuilder::CACHE_PATH);
            $cachedRoutes = $cache->get(BuildRouteMatcher::CACHE_KEY);
            $routes = [];
            if (
                KernelAbstract::ENV_PROD === $container->get(AppDIContainerBuilder::APP_ENV_CONFIGURATION_KEY) &&
                null !== $cachedRoutes
            ) {
                $logger->debug('Routes loaded from cache');
                $routes = $cachedRoutes;
            }
            if (empty($routes)) {
                //@TODO: extract route parsing to the external class
                //app config (normal priority)
                foreach (BuildRouteMatcher::ROUTE_LOCATIONS as $routeLocation) {
                    foreach (glob($projectDir . $routeLocation) as $routeFile) {
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
                $cache->set(BuildRouteMatcher::CACHE_KEY, $routes);
                $logger->notice('Routes analyzed, cache written');
            }
            return (new RouteMatcher($container->get(LoggerInterface::class)))->setRoutes($routes);
        }]);
    }
}
