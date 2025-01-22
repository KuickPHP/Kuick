<?php

namespace Tests\Unit\Kuick\Framework\Events;

use Kuick\Framework\Events\KernelCreatedEvent;
use Tests\Unit\Kuick\Framework\Mocks\MockKernel;
use Monolog\Test\TestCase;

/**
 * @covers Kuick\Framework\Events\KernelCreatedEvent
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
