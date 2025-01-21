<?php

namespace Tests\Kuick\Unit\Framework\Events;

use Kuick\Framework\Events\CommandReceivedEvent;
use Tests\Kuick\Unit\Mocks\MockKernel;
use Monolog\Test\TestCase;

/**
 * @covers \Kuick\Framework\Events\CommandReceivedEvent
 */
class CommandReceivedEventTest extends TestCase
{
    public function testIfKernelCanBeRetrievedFromTheEvent(): void
    {
        $kernel = new MockKernel();
        $event = new CommandReceivedEvent($kernel);
        $this->assertEquals($kernel, $event->getKernel());
    }
}
