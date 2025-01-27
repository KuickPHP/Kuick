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
use Kuick\Framework\Config\ConfigException;
use Kuick\Framework\Config\ListenerConfig;
use Kuick\Framework\Kernel;
use Kuick\Framework\SystemCacheInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class ListenersBuilder
{
    public const CONFIG_SUFFIX = 'listeners';

    public function __construct(private ContainerBuilder $builder)
    {
    }

    public function __invoke(): void
    {
        $this->builder->addDefinitions([Kernel::DI_LISTENERS_KEY => function (ContainerInterface $container, LoggerInterface $logger, SystemCacheInterface $cache) {
            $validatedListeners = [];
            foreach ((new ConfigIndexer($cache, $logger))->getConfigFiles($container->get(Kernel::DI_PROJECT_DIR_KEY), ListenersBuilder::CONFIG_SUFFIX) as $listenersFile) {
                $listeners = include $listenersFile;
                foreach ($listeners as $listener) {
                    if (!($listener instanceof ListenerConfig)) {
                        throw new ConfigException('Listener config must be an instance of ' . ListenerConfig::class);
                    }
                    $logger->debug('Adding listener: ' . $listener->listenerClassName . ' for ' . $listener->pattern);
                    $validatedListeners[] = $listener;
                }
            }
            return $validatedListeners;
        }]);
    }
}
