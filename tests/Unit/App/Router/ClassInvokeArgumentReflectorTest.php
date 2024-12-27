<?php

namespace Tests\Kuick\App\Router;

use Kuick\App\AppException;
use Kuick\App\Router\ClassInvokeArgumentReflector;
use PHPUnit\Framework\TestCase;
use Tests\Kuick\Mocks\ControllerMock;
use Tests\Kuick\Mocks\InvalidGuardMock;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\App\Router\ClassInvokeArgumentReflector
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
