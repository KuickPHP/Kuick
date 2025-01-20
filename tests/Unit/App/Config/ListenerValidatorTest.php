<?php

namespace Kuick\Tests\App\Config;

use Kuick\App\Config\ConfigException;
use Kuick\App\Config\ListenerConfig;
use Kuick\App\Config\ListenerValidator;
use Kuick\Tests\Mocks\MockListener;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kuick\App\Config\ListenerValidator
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
