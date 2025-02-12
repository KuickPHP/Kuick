<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\DependencyInjection\Factories;

use DI\ContainerBuilder;
use Kuick\Framework\Config\ConfigIndexer;
use Kuick\Routing\Router;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Router factory
 */
class RouterFactory
{
    public function build(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            Router::class => function (
                ConfigIndexer $configIndexer,
                ContainerInterface $container,
                LoggerInterface $logger
            ): Router {
                $router = new Router($logger);
                foreach ($configIndexer->getConfigFilePaths(ConfigIndexer::ROUTES_FILE_SUFFIX) as $routeFile) {
                    foreach (require $routeFile as $route) {
                        $logger->debug('Adding route: ' . $route->path, $route->methods);
                        $router->addRoute($route->path, $container->get($route->controllerClassName), $route->methods);
                    }
                }
                $logger->debug('Router initialized');
                return $router;
            }
        ]);
    }
}
