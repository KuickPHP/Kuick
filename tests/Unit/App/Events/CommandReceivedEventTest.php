<?php

namespace Kuick\Tests\App\Events;

use Kuick\App\Events\CommandReceivedEvent;
use Kuick\Tests\Mocks\MockKernel;
use Monolog\Test\TestCase;
use Nyholm\Psr7\ServerRequest;

/**
 * @covers \Kuick\App\Events\CommandReceivedEvent
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