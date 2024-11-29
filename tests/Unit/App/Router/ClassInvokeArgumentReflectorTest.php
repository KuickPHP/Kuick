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
        $ai = new ClassInvokeArgumentReflector;
        $this->expectException(AppException::class);
        $ai->__invoke(InvalidGuardMock::class);
    }

    public function testIfMockControllerInvokeContainsNameArgument(): void
    {
        $ai = new ClassInvokeArgumentReflector;
        assertEquals(['userId' => ['type' => 'int', 'default' => null, 'isOptional' => false]], $ai->__invoke(ControllerMock::class));
    }
}
