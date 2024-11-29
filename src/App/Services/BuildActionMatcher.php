<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Services;

use Kuick\App\KernelAbstract;
use Kuick\App\Router\ActionInvokeArgumentReflector;
use Kuick\App\Router\RouteMatcher;
use Kuick\App\Router\RouteValidator;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 *
 */
class BuildActionMatcher extends ServiceBuildAbstract
{
    public function __invoke(): void
    {
        $this->builder->addDefinitions([RouteMatcher::class => function (ContainerInterface $container): RouteMatcher {
            $env = getenv(KernelAbstract::APP_ENV);
            $routes = CacheWrapper::load($env, __CLASS__);
            if (null === $routes) {
                $routes = [];
                //app config (normal priority)
                foreach (glob(BASE_PATH . '/etc/*.routes.php') as $routeFile) {
                    $routes = array_merge($routes, include $routeFile);
                }
                //validating routes
                //decorating routes with available controller methods
                foreach ($routes as $key => $route) {
                    (new RouteValidator())($route);
                    $routes[$key]['invokeParams'] = (new ActionInvokeArgumentReflector())($route['controller']);
                }
                CacheWrapper::save($env, __CLASS__, $routes);
            }
            $routeMatcher = (new RouteMatcher($container->get(LoggerInterface::class)))->setRoutes($routes);
            return $routeMatcher;
        }]);
    }
}
