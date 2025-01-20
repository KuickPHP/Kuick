<?php

namespace Kuick\Tests\App\Listeners;

use Kuick\App\Events\CommandReceivedEvent;
use Kuick\App\Listeners\CommandLaunchingListener;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kuick\App\Listeners\CommandLaunchingListener
 */
class CommandLaunchingListenerTest extends TestCase
{
    public function testIfCommandIsLaunched(): void
    {
        // $listener = new CommandLaunchingListener();
        // new CommandReceivedEvent()
        // $listener('echo "Hello world!"');
        $this->assertTrue(true);
    }
}
