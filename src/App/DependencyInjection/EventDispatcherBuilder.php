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
use Kuick\App\Config\ListenerConfig;
use Kuick\App\Kernel;
use Kuick\App\SystemCacheInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class EventDispatcherBuilder
{
    public const CONFIG_SUFFIX = 'listeners';

    public function __construct(private ContainerBuilder $builder)
    {
    }

    public function __invoke(): void
    {
        $this->builder->addDefinitions([Kernel::DI_LISTENERS_KEY => function (ContainerInterface $container, LoggerInterface $logger, SystemCacheInterface $cache) {
            $validatedListeners = [];
            foreach ((new ConfigIndexer($cache, $logger))->getConfigFiles($container->get(Kernel::DI_PROJECT_DIR_KEY), EventDispatcherBuilder::CONFIG_SUFFIX) as $listenersFile) {
                $listeners = include $listenersFile;
                foreach ($listeners as $listener) {
                    if (!($listener instanceof ListenerConfig)) {
                        throw new ConfigException('Listener config must be an instance of ' . ListenerConfig::class);
                    }
                    $validatedListeners[] = $listener;
                }
            }
            return $validatedListeners;
        }]);
    }
}
