<?php

namespace Tests\Unit\Kuick\Framework\Config;

use Kuick\Framework\Config\ConfigException;
use Kuick\Framework\Config\ListenerConfig;
use Kuick\Framework\Config\ListenerConfigValidator;
use Tests\Unit\Kuick\Framework\Mocks\MockListener;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers Kuick\Framework\Config\ListenerConfigValidator
 */
class ListenerConfigValidatorTest extends TestCase
{
    public function testIfCorrectListenerConfigValidatorDoesNothing(): void
    {
        $listenerConfig = new ListenerConfig('*', MockListener::class);
        (new ListenerConfigValidator())->validate($listenerConfig);
        $this->assertTrue(true);
    }

    public function testIfEmptyPathRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Listener pattern should not be empty');
        (new ListenerConfigValidator())->validate(new ListenerConfig('', MockListener::class));
    }

    public function testIfEmptyListenerClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Listener class name should not be empty');
        (new ListenerConfigValidator())->validate(new ListenerConfig('*', ''));
    }

    public function testIfInexistentListenerClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Listener class: "InexistentListener" does not exist');
        (new ListenerConfigValidator())->validate(new ListenerConfig('*', 'InexistentListener'));
    }

    public function testIfNotInvokableListenerClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Listener class: "stdClass" is not invokable');
        (new ListenerConfigValidator())->validate(new ListenerConfig('*', 'stdClass'));
    }
}
