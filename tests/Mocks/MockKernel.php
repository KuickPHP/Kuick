<?php

namespace Kuick\Tests\Mocks;

use Kuick\App\KernelInterface;
use Kuick\EventDispatcher\EventDispatcher;
use Kuick\EventDispatcher\ListenerProvider;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class MockKernel implements KernelInterface
{
    private ContainerInterface $container;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(public readonly string $projectDir = '/tmp')
    {
        $this->container = new MockContainer();
        $this->eventDispatcher = new EventDispatcher(new ListenerProvider());
    }

    public function getContainer(): MockContainer
    {
        return $this->container;
    }

    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }
}