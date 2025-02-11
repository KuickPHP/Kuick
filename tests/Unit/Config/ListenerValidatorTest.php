<?php

namespace Tests\Unit\Kuick\Framework\Config;

use Kuick\Framework\Config\ConfigException;
use Kuick\Framework\Config\ListenerConfig;
use Kuick\Framework\Config\ListenerValidator;
use Tests\Unit\Kuick\Framework\Mocks\MockListener;
use PHPUnit\Framework\TestCase;

/**
 * @covers Kuick\Framework\Config\ListenerValidator
 */
class ListenerValidatorTest extends TestCase
{
    public function testIfCorrectListenerValidatorDoesNothing(): void
    {
        $listenerConfig = new ListenerConfig('*', MockListener::class);
        (new ListenerValidator())->validate($listenerConfig);
        $this->assertTrue(true);
    }

    public function testIfEmptyPathRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Listener pattern should not be empty');
        (new ListenerValidator())->validate(new ListenerConfig('', MockListener::class));
    }

    public function testIfEmptyListenerClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Listener class name should not be empty');
        (new ListenerValidator())->validate(new ListenerConfig('*', ''));
    }

    public function testIfInexistentListenerClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Listener class: "InexistentListener" does not exist');
        (new ListenerValidator())->validate(new ListenerConfig('*', 'InexistentListener'));
    }

    public function testIfInvalidConfigClassRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Kuick\Framework\Config\ListenerConfig expected, object given');
        (new ListenerValidator())->validate(new \stdClass());
    }

    public function testIfNotInvokableListenerClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Listener class: "stdClass" is not invokable');
        (new ListenerValidator())->validate(new ListenerConfig('*', 'stdClass'));
    }
}
