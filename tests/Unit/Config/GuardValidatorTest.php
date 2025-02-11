<?php

namespace Tests\Unit\Kuick\Framework\Config;

use Kuick\Framework\Config\ConfigException;
use Kuick\Framework\Config\GuardConfig;
use Kuick\Framework\Config\GuardValidator;
use Tests\Unit\Kuick\Framework\Mocks\MockGuard;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers Kuick\Framework\Config\GuardValidator
 */
class GuardValidatorTest extends TestCase
{
    public function testIfCorrectGuardValidatorDoesNothing(): void
    {
        $guardConfig = new GuardConfig('/test', MockGuard::class);
        (new GuardValidator())->validate($guardConfig);
        $this->assertTrue(true);
    }

    public function testIfEmptyPathRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Guard path should not be empty');
        (new GuardValidator())->validate(new GuardConfig('', MockGuard::class));
    }

    public function testIfInvalidConfigClassRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Kuick\Framework\Config\GuardConfig expected, object given');
        (new GuardValidator())->validate(new stdClass());
    }

    public function testIfInvalidMethodRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Guard method invalid, path: /test, method: INVALID');
        (new GuardValidator())->validate(new GuardConfig('/test', MockGuard::class, ['INVALID']));
    }

    public function testIfEmptyGuardClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Guard class name should not be empty, path: /test');
        (new GuardValidator())->validate(new GuardConfig('/test', ''));
    }

    public function testIfInexistentGuardClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Guard class: "InexistentGuard" does not exist, path: /test');
        (new GuardValidator())->validate(new GuardConfig('/test', 'InexistentGuard'));
    }

    public function testIfNotInvokableGuardClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Guard class: "stdClass" is not invokable, path: /test');
        (new GuardValidator())->validate(new GuardConfig('/test', 'stdClass'));
    }

    public function testIfInvalidPatternRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Guard path should be a valid regex pattern');
        (new GuardValidator())->validate(new GuardConfig('([a-z][[a-z]', MockGuard::class));
    }
}
