<?php

namespace Tests\Kuick\App;

use Kuick\App\ConsoleKernel;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\App\ConsoleKernel
 * @covers \Kuick\App\KernelAbstract
 */
class ConsoleKernelTest extends TestCase
{
    /**
     * Needs to be run in separate process, Container throws an error instead
     * @runInSeparateProcess
     */
    public function testIfConsoleConfiguresEnvironmentCorrectly(): void
    {
        new ConsoleKernel();
        assertEquals(0, ini_get('max_execution_time'));
    }
}
