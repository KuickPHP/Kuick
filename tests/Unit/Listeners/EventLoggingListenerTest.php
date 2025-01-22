<?php

namespace Tests\Unit\Kuick\Framework\Listeners;

use Kuick\Framework\Listeners\EventLoggingListener;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use stdClass;

/**
 * @covers Kuick\Framework\Listeners\EventLoggingListener
 */
class EventLoggingListenerTest extends TestCase
{
    public function testIfStdClassEventIsLogged(): void
    {
        $listener = new EventLoggingListener(new NullLogger());
        $listener(new stdClass());
        $this->assertTrue(true);
    }
}
