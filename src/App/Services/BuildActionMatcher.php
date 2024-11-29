<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Services;

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
            $actionMatcher = (new RouteMatcher($container->get(LoggerInterface::class)))->setRoutes($routes);
            return $actionMatcher;
        }]);
    }
}
