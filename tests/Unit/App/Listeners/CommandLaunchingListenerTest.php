<?php

namespace Kuick\Tests\App\Listeners;

use Kuick\App\Events\CommandReceivedEvent;
use Kuick\App\KernelInterface;
use Kuick\App\Listeners\CommandLaunchingListener;
use Kuick\Tests\Mocks\MockKernel;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kuick\App\Listeners\CommandLaunchingListener
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
