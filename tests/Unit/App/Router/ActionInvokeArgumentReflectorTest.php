<?php

namespace Tests\Kuick\App\Router;

use Kuick\App\AppException;
use Kuick\App\Router\ActionInvokeArgumentReflector;
use PHPUnit\Framework\TestCase;
use Tests\Kuick\Mocks\ControllerMock;
use Tests\Kuick\Mocks\InvalidGuardMock;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\App\Router\ActionInvokeArgumentReflector
 */
class ActionInvokeArgumentReflectorTest extends TestCase
{
    public function testIfMissingInvokeThrowsException(): void
    {
        $ai = new ActionInvokeArgumentReflector;
        $this->expectException(AppException::class);
        $ai->__invoke(InvalidGuardMock::class);
    }

    public function testIfMockControllerInvokeContainsNameArgument(): void
    {
        $ai = new ActionInvokeArgumentReflector;
        assertEquals(['userId' => ['type' => 'int', 'default' => null]], $ai->__invoke(ControllerMock::class));
    }
}
