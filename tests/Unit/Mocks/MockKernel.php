<?php

namespace Tests\Unit\Kuick\Framework\Mocks;

use Kuick\Framework\KernelInterface;

class MockKernel implements KernelInterface
{
    private MockContainer $container;

    public function __construct(private string $projectDir = '/tmp')
    {
        $this->container = new MockContainer();
    }

    public function getContainer(): MockContainer
    {
        return $this->container;
    }

    public function getProjectDir(): string
    {
        return $this->projectDir;
    }
}
