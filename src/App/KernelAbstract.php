<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use Kuick\App\Events\KernelCreated;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Abstract Application Kernel
 */
abstract class KernelAbstract implements KernelInterface
{
    private ContainerInterface $container;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(public readonly string $projectDir)
    {
        //building DI container
        $this->container = (new AppDIContainerBuilder())($projectDir);
        $this->eventDispatcher = $this->container->get(EventDispatcherInterface::class);
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
