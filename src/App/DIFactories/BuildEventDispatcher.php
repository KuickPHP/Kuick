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
use Kuick\App\DIFactories\Utils\ListenerConfigLoader;
use Kuick\Event\EventDispatcher;
use Kuick\Event\ListenerPriority;
use Kuick\Event\ListenerProvider;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

class BuildEventDispatcher extends FactoryAbstract
{
    public function __invoke(): void
    {
        $this->builder->addDefinitions([EventDispatcherInterface::class => function (ContainerInterface $container): EventDispatcherInterface {
            $listenerProvider = new ListenerProvider();
            $logger = $container->get(LoggerInterface::class);
            foreach ((new ListenerConfigLoader($logger))(
                $container->get(AppDIContainerBuilder::PROJECT_DIR_CONFIGURATION_KEY),
                $container->get(AppDIContainerBuilder::APP_ENV_CONFIGURATION_KEY)
            ) as $listener) {
                $listenerProvider->registerListener(
                    $listener['pattern'], 
                    $container->get($listener['listener']),
                    $listener['priority'] ?? ListenerPriority::NORMAL
                );
            }
            return new EventDispatcher($listenerProvider);
        }]);
    }
}