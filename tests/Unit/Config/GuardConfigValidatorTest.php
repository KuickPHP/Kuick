<?php

namespace Tests\Unit\Kuick\Framework\Config;

use Kuick\Framework\Config\ConfigException;
use Kuick\Framework\Config\GuardConfig;
use Kuick\Framework\Config\GuardConfigValidator;
use Tests\Unit\Kuick\Framework\Mocks\MockGuard;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers Kuick\Framework\Config\GuardConfigValidator
 */
class GuardConfigValidatorTest extends TestCase
{
    public function testIfCorrectGuardConfigValidatorDoesNothing(): void
    {
        $guardConfig = new GuardConfig('/test', MockGuard::class);
        (new GuardConfigValidator())->validate($guardConfig);
        $this->assertTrue(true);
    }

    public function testIfEmptyPathRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Guard path should not be empty');
        (new GuardConfigValidator())->validate(new GuardConfig('', MockGuard::class));
    }

    public function testIfInvalidMethodRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Guard method invalid, path: /test, method: INVALID');
        (new GuardConfigValidator())->validate(new GuardConfig('/test', MockGuard::class, ['INVALID']));
    }

    public function testIfEmptyGuardClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Guard class name should not be empty, path: /test');
        (new GuardConfigValidator())->validate(new GuardConfig('/test', ''));
    }

    public function testIfInexistentGuardClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Guard class: "InexistentGuard" does not exist, path: /test');
        (new GuardConfigValidator())->validate(new GuardConfig('/test', 'InexistentGuard'));
    }

    public function testIfNotInvokableGuardClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Guard class: "stdClass" is not invokable, path: /test');
        (new GuardConfigValidator())->validate(new GuardConfig('/test', 'stdClass'));
    }

    public function testIfInvalidPatternRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Guard path should be a valid regex pattern');
        (new GuardConfigValidator())->validate(new GuardConfig('([a-z][[a-z]', MockGuard::class));
    }
}
