<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use Kuick\App\DependencyInjection\ContainerCreator;
use Kuick\App\Events\KernelCreated;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * Application Kernel
 */
class Kernel implements KernelInterface
{
    private ContainerInterface $container;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(public readonly string $projectDir)
    {
        //building DI container
        $this->container = (new ContainerCreator())($projectDir);
        $this->eventDispatcher = $this->container->get(EventDispatcherInterface::class);
        $listenerProvider = $this->container->get(ListenerProviderInterface::class);
        //registering listeners "on the fly", as they can be dependent on the EventDispatcher
        foreach ($this->container->get(self::DI_LISTENERS_KEY) as $listener) {
            $listenerProvider->registerListener($listener->pattern, $this->container->get($listener->callable), $listener->priority);
        }
        $this->eventDispatcher->dispatch(new KernelCreated($this));
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }
}
