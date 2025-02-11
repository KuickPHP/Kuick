<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\DependencyInjection;

use DI\ContainerBuilder;
use Kuick\Framework\Config\RouteValidator;
use Kuick\Framework\SystemCache;
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
            foreach ($configIndexer->getConfigFiles(RouterBuilder::CONFIG_SUFFIX, new RouteValidator()) as $routeFile) {
                foreach (require $routeFile as $route) {
                    $logger->debug('Adding route: ' . $route->path, $route->methods);
                    $router->addRoute($route->path, $container->get($route->controllerClassName), $route->methods);
                }
            }
            $logger->debug('Router initialized');
            return $router;
        }]);
    }
}
