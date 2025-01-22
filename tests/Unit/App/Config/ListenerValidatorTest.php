<?php

namespace Tests\Kuick\Unit\Framework\Config;

use Kuick\Framework\Config\ConfigException;
use Kuick\Framework\Config\ListenerConfig;
use Kuick\Framework\Config\ListenerValidator;
use Tests\Kuick\Unit\Mocks\MockListener;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kuick\Framework\Config\ListenerValidator
 */
class ListenerValidatorTest extends TestCase
{
    public function testIfCorrectListenerValidatorDoesNothing(): void
    {
        $listenerConfig = new ListenerConfig('*', MockListener::class);
        new ListenerValidator($listenerConfig);
        $this->assertTrue(true);
    }

    public function testIfEmptyPathRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Listener pattern should not be empty');
        new ListenerValidator(new ListenerConfig('', MockListener::class));
    }

    public function testIfEmptyListenerClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Listener class name should not be empty');
        new ListenerValidator(new ListenerConfig('*', ''));
    }

    public function testIfInexistentListenerClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Listener class: "InexistentListener" does not exist');
        new ListenerValidator(new ListenerConfig('*', 'InexistentListener'));
    }

    public function testIfNotInvokableListenerClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Listener class: "stdClass" is not invokable');
        new ListenerValidator(new ListenerConfig('*', 'stdClass'));
    }
}
