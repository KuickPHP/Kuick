<?php

namespace Kuick\Tests\App\Events;

use Kuick\App\Events\KernelCreatedEvent;
use Kuick\Tests\Mocks\MockKernel;
use Monolog\Test\TestCase;

/**
 * @covers \Kuick\App\Events\KernelCreatedEvent
 */
class KernelCreatedEventTest extends TestCase
{
    /**
     * Needs to be run in separate process, cause emmiter sends headers
     * @runInSeparateProcess
     */
    public function testIfKernelObjectCanBeRetrievedFromTheEvent(): void
    {
        $kernel = new MockKernel();
        $event = new KernelCreatedEvent($kernel);
        $this->assertEquals($kernel, $event->getKernel());
    }
}