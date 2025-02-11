<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework;

use Kuick\Framework\DependencyInjection\ContainerCreator;
use Kuick\Framework\Events\KernelCreatedEvent;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Log\LoggerInterface;

/**
 * Application Kernel
 */
class Kernel implements KernelInterface
{
    private ContainerInterface $container;

    public function __construct(private string $projectDir)
    {
        // building DI container
        $this->container = (new ContainerCreator())->create($projectDir);
        $logger = $this->container->get(LoggerInterface::class);
        // registering listeners "on the fly", as they can depend on EventDispatcher
        foreach ($this->container->get(self::DI_LISTENERS_KEY) as $listener) {
            $this->container->get(ListenerProviderInterface::class)->registerListener(
                $listener->pattern,
                $this->container->get($listener->listenerClassName),
                $listener->priority
            );
            $logger->debug('Listener registered: ' . $listener->listenerClassName);
        }
        $logger->debug('Listener provider initialized');
        $this->container->get(EventDispatcherInterface::class)->dispatch(new KernelCreatedEvent($this));
    }

    public function getProjectDir(): string
    {
        return $this->projectDir;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
