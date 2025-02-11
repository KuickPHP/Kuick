<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\DependencyInjection;

use Closure;
use DI\ContainerBuilder;
use Kuick\Framework\Config\ConfigException;
use Kuick\Framework\Config\RouteConfig;
use Kuick\Framework\Config\RouteValidator;
use Kuick\Framework\Kernel;
use Kuick\Framework\SystemCacheInterface;
use Kuick\Routing\Router;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 *
 */
class RouterBuilder
{
    public const CONFIG_SUFFIX = 'routes';

    public function __construct(private ContainerBuilder $builder)
    {
    }

    public function __invoke(): void
    {
        $this->builder->addDefinitions([Router::class =>
        function (
            ConfigIndexer $configIndexer,
            ContainerInterface $container,
            LoggerInterface $logger
        ): Router {
            $router = new Router($logger);
            $logger = $container->get(LoggerInterface::class);
            foreach ($configIndexer->getConfigFiles(RouterBuilder::CONFIG_SUFFIX, new RouteValidator()) as $routeFile) {
                $routes = include $routeFile;
                foreach ($routes as $route) {
                    if (!$route instanceof RouteConfig) {
                        throw new ConfigException('Route config must be an instance of' . RouteConfig::class);
                    }
                    $logger->debug('Adding route: ' . $route->path, $route->methods);
                    // getting from container if controller is a string
                    $callable = $route->controller instanceof Closure ?
                        $route->controller :
                        $container->get($route->controller);
                    $router->addRoute($route->path, $callable, $route->methods);
                }
            }
            $logger->debug('Router initialized');
            return $router;
        }]);
    }
}
