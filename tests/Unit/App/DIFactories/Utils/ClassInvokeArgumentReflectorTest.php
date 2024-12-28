<?php

namespace Kuick\Tests\App\DIFactories\Utils;

use Kuick\App\AppException;
use Kuick\App\DIFactories\Utils\ClassInvokeArgumentReflector;
use PHPUnit\Framework\TestCase;
use Kuick\Tests\Mocks\ControllerMock;
use Kuick\Tests\Mocks\InvalidGuardMock;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\App\DIFactories\Utils\ClassInvokeArgumentReflector
 */
class ClassInvokeArgumentReflectorTest extends TestCase
{
    public function testIfMissingInvokeThrowsException(): void
    {
        $air = new ClassInvokeArgumentReflector();
        $this->expectException(AppException::class);
        $air(InvalidGuardMock::class);
    }

    public function testIfMockControllerInvokeContainsNameArgument(): void
    {
        $air = new ClassInvokeArgumentReflector();
        assertEquals(['userId' => ['type' => 'int', 'default' => null, 'isOptional' => false]], $air(ControllerMock::class));
    }
}
