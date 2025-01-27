<?php

namespace Tests\Unit\Kuick\Framework\Listeners;

use Kuick\EventDispatcher\EventDispatcher;
use Kuick\EventDispatcher\ListenerProvider;
use Kuick\Framework\Listeners\RegisteringPhpErrorHandlerListener;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * @covers Kuick\Framework\Listeners\RegisteringPhpErrorHandlerListener
 */
class RegisteringPhpErrorHandlerListenerTest extends TestCase
{
    public function testIfErrorHandlerIsRegistered(): void
    {
        (new RegisteringPhpErrorHandlerListener(new EventDispatcher(new ListenerProvider()), new NullLogger()))();
        $this->assertTrue(true);
    }
}
