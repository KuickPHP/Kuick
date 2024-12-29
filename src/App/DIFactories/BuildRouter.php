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
use Kuick\App\DIFactories\Utils\RouteParser;
use Kuick\Http\Server\Router;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 *
 */
class BuildRouter extends FactoryAbstract
{
    public function __invoke(): void
    {
        $this->builder->addDefinitions([Router::class => function (ContainerInterface $container): Router {
            $logger = $container->get(LoggerInterface::class);
            return (new Router($logger))->setRoutes(
                (new RouteParser($logger))(
                    $container->get(AppDIContainerBuilder::PROJECT_DIR_CONFIGURATION_KEY),
                    $container->get(AppDIContainerBuilder::APP_ENV_CONFIGURATION_KEY)
                )
            );
        }]);
    }
}
