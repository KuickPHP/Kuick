<?php

namespace Tests\Unit\Kuick\Framework;

use Kuick\Framework\Kernel;
use Kuick\Framework\KernelInterface;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @covers Kuick\Framework\Kernel
 */
class Kernelest extends TestCase
{
    private static string $projectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(__DIR__ . '/Mocks/project-dir');
    }

    /**
     * Needs to be run in separate process, cause emmiter sends headers
     * @runInSeparateProcess
     */
    public function testIfDevKernelIsWellDefined(): void
    {
        putenv('APP_ENV=dev');
        $kernel = new Kernel(self::$projectDir);
        $this->assertInstanceOf(KernelInterface::class, $kernel);
        $this->assertInstanceOf(ContainerInterface::class, $container = $kernel->getContainer());
        $this->assertInstanceOf(EventDispatcherInterface::class, $container->get(EventDispatcherInterface::class));
        $this->assertEquals('Testing App', $container->get('kuick.app.name'));
        $this->assertEquals($container->get('kuick.app.projectDir'), $kernel->getProjectDir());
        $this->assertEquals('dev', $container->get('kuick.app.env'));
        $this->assertEquals('Europe/Warsaw', $container->get('kuick.app.timezone'));
    }

    /**
     * Needs to be run in separate process, cause emmiter sends headers
     * @runInSeparateProcess
     */
    public function testIfTestKernelIsWellDefined(): void
    {
        putenv('APP_ENV=test');
        $kernel = new Kernel(self::$projectDir);
        $this->assertInstanceOf(KernelInterface::class, $kernel);
        $this->assertInstanceOf(ContainerInterface::class, $container = $kernel->getContainer());
        $this->assertInstanceOf(EventDispatcherInterface::class, $container->get(EventDispatcherInterface::class));
        $this->assertEquals('test', $container->get('kuick.app.env'));
        $this->assertEquals('Europe/London', $container->get('kuick.app.timezone'));
        (new Filesystem())->remove(self::$projectDir . '/var/cache');
    }
}
