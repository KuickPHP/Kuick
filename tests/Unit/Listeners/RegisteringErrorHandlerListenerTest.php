<?php

namespace Tests\Unit\Kuick\Framework\Listeners;

use Exception;
use Kuick\EventDispatcher\EventDispatcher;
use Kuick\EventDispatcher\ListenerProvider;
use Kuick\Framework\Events\ResponseCreatedEvent;
use Kuick\Framework\Listeners\RegisteringErrorHandlerListener;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * @covers Kuick\Framework\Listeners\RegisteringErrorHandlerListener
 */
class RegisteringErrorHandlerListenerTest extends TestCase
{
    public function testIfStdClassEventIsLogged(): void
    {
        $listenerProvider = new ListenerProvider();
        $listener = new RegisteringErrorHandlerListener(new EventDispatcher($listenerProvider), new NullLogger());
        $listener();
        $this->expectException(Exception::class);
        throw new Exception();
    }
}
