<?php

namespace Kuick\Tests\App\Config;

use Kuick\App\Config\ListenerConfig;
use Kuick\EventDispatcher\ListenerPriority;
use Kuick\Tests\Mocks\MockListener;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kuick\App\Config\ListenerConfig
 */
class ListenerConfigTest extends TestCase
{
    public function testIfListenerConfigIsDefinedWithTheDefaultMethods(): void
    {
        $listenerConfig = new ListenerConfig('*', MockListener::class);
        $this->assertEquals('*', $listenerConfig->pattern);
        $this->assertEquals(MockListener::class, $listenerConfig->listenerClassName);
        $this->assertEquals(0, $listenerConfig->priority);
        $anotherConfig = new ListenerConfig('*', MockListener::class, ListenerPriority::HIGH);
        $this->assertEquals(ListenerPriority::HIGH, $anotherConfig->priority);
    }
}
