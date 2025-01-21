<?php

namespace Kuick\Tests\App;

use Kuick\App\Kernel;
use Kuick\App\KernelInterface;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @covers \Kuick\App\Kernel
 */
class Kernelest extends TestCase
{
    private static string $projectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(dirname(__DIR__) . '/../Mocks/project-dir');
        (new Filesystem())->remove(self::$projectDir . '/var/cache');
    }

    /**
     * Needs to be run in separate process, cause emmiter sends headers
     * @runInSeparateProcess
     */
    public function testIfProdKernelIsWellDefined(): void
    {
        $kernel = new Kernel(self::$projectDir);
        $this->assertInstanceOf(KernelInterface::class, $kernel);
        $container = $kernel->getContainer();
        $this->assertInstanceOf(EventDispatcherInterface::class, $kernel->getEventDispatcher());
        $this->assertEquals('/var/www/html/tests/Mocks/project-dir', $kernel->getProjectDir());
        $this->assertEquals('Testing App', $container->get('kuick.app.name'));
        $this->assertEquals('prod', $container->get('kuick.app.env'));
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
        $this->assertEquals('dev', $container->get('kuick.app.env'));
    }
}
