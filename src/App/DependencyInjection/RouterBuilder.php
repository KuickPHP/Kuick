<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\DependencyInjection;

use DI\ContainerBuilder;
use Kuick\App\Config\ConfigException;
use Kuick\App\Config\RouteConfig;
use Kuick\App\Kernel;
use Kuick\App\SystemCacheInterface;
use Kuick\Routing\Router;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 *
 */
class RouterBuilder
{
    public function __construct(private ContainerBuilder $builder)
    {
    }

    public function __invoke(): void
    {
        $this->builder->addDefinitions([Router::class => function (ContainerInterface $container, LoggerInterface $logger, SystemCacheInterface $cache): Router {
            $router = new Router($logger);
            $logger = $container->get(LoggerInterface::class);
            foreach ((new ConfigIndexer($cache, $logger))->getConfigFiles($container->get(Kernel::DI_PROJECT_DIR_KEY), 'routes') as $routeFile) {
                $routes = include $routeFile;
                foreach ($routes as $route) {
                    if (!$route instanceof RouteConfig) {
                        throw new ConfigException('Invalid route config');
                    }
                    $logger->info('Adding route: ' . $route->path);
                    $router->addRoute($route->path, $container->get($route->controllerClassName), $route->methods);
                }
            }
            return $router;
        }]);
    }
}
