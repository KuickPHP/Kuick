<?php

namespace Tests\Unit\Kuick\Framework\Mocks;

use Kuick\Framework\KernelInterface;
use Kuick\EventDispatcher\EventDispatcher;
use Kuick\EventDispatcher\ListenerProvider;
use Psr\EventDispatcher\EventDispatcherInterface;

class MockKernel implements KernelInterface
{
    private MockContainer $container;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(private string $projectDir = '/tmp')
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

    public function getProjectDir(): string
    {
        return $this->projectDir;
    }
}
