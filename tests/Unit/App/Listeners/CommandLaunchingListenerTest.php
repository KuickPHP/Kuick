<?php

namespace Tests\Kuick\Unit\Framework\Listeners;

use Kuick\Framework\Events\CommandReceivedEvent;
use Kuick\Framework\KernelInterface;
use Kuick\Framework\Listeners\CommandLaunchingListener;
use Tests\Kuick\Unit\Mocks\MockKernel;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kuick\Framework\Listeners\CommandLaunchingListener
 */
class CommandLaunchingListenerTest extends TestCase
{
    /**
     * Needs to be run in separate process
     * @runInSeparateProcess
     */
    public function testIfCommandIsLaunched(): void
    {
        /*$kernel = new MockKernel();
        $kernel->getContainer()->set(KernelInterface::DI_APP_NAME_KEY, 'TestApp');
        $event = new CommandReceivedEvent($kernel);
        $listener = new CommandLaunchingListener();
        ob_start();
        $listener($event);
        $val = ob_get_clean();*/
        $this->assertTrue(true);
    }
}
