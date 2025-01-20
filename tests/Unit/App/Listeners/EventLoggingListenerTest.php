<?php

namespace Kuick\Tests\App\Listeners;

use Kuick\App\Listeners\EventLoggingListener;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use stdClass;

/**
 * @covers \Kuick\App\Listeners\EventLoggingListener
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
