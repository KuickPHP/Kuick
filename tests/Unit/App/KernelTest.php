<?php

namespace Tests\Kuick\Unit\Framework;

use Kuick\Framework\Kernel;
use Kuick\Framework\KernelInterface;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * @covers Kuick\Framework\Kernel
 */
class Kernelest extends TestCase
{
    private static string $projectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(dirname(__DIR__) . '/Mocks/project-dir');
    }

    /**
     * Needs to be run in separate process, cause emmiter sends headers
     * @runInSeparateProcess
     */
    public function testIfDevKernelIsWellDefined(): void
    {
        putenv('KUICK_APP_ENV=dev');
        $kernel = new Kernel(self::$projectDir);
        $this->assertInstanceOf(KernelInterface::class, $kernel);
        $container = $kernel->getContainer();
        $this->assertInstanceOf(EventDispatcherInterface::class, $kernel->getEventDispatcher());
        $this->assertEquals('Testing App', $container->get('kuick.app.name'));
        $this->assertEquals($container->get('kuick.app.projectDir'), $kernel->getProjectDir());
        $this->assertEquals('dev', $container->get('kuick.app.env'));
        $this->assertEquals('Europe/Warsaw', $container->get('kuick.app.timezone'));
    }
}
