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
            $routes = (new RoutesConfigLoader($cache, $logger))($container->get(Kernel::DI_PROJECT_DIR_KEY));
            $router = new Router($logger);
            foreach ($routes as $route) {
                $router->addRoute($route->path, $container->get($route->controllerClassName), $route->methods);
            }
            return $router;
        }]);
    }
}
