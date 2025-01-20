<?php

namespace Kuick\Tests\App\Config;

use Kuick\App\Config\GuardConfig;
use Kuick\Tests\Mocks\MockGuard;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kuick\App\Config\GuardConfig
 */
class GuardConfigTest extends TestCase
{
    public function testIfGuardConfigIsDefinedWithTheDefaultMethods(): void
    {
        $guardConfig = new GuardConfig('/test', MockGuard::class);
        $this->assertEquals('/test', $guardConfig->path);
        $this->assertEquals(MockGuard::class, $guardConfig->guardClassName);
        $this->assertEquals([
            'GET',
            'OPTIONS',
            'POST',
            'PUT',
            'PATCH',
            'DELETE',
        ], $guardConfig->methods);
    }
}
