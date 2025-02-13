<?php

namespace Tests\Unit\Kuick\Framework;

use Kuick\Framework\WebKernel;
use Kuick\Framework\KernelInterface;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @covers Kuick\Framework\WebKernel
 * @covers Kuick\Framework\KernelAbstract
 */
class WebKernelTest extends TestCase
{
    public static string $projectDir;

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
        $kernel = new WebKernel(self::$projectDir);
        $this->assertInstanceOf(KernelInterface::class, $kernel);
        $this->assertInstanceOf(ContainerInterface::class, $container = $kernel->getContainer());
        $this->assertInstanceOf(EventDispatcherInterface::class, $container->get(EventDispatcherInterface::class));
        $this->assertEquals('Testing App', $container->get('app.name'));
        $this->assertEquals('dev', $container->get('app.env'));
        $this->assertEquals('Europe/Warsaw', $container->get('app.timezone'));
    }

    /**
     * Needs to be run in separate process, cause emmiter sends headers
     * @runInSeparateProcess
     */
    public function testIfTestKernelIsWellDefined(): void
    {
        putenv('APP_ENV=test');
        $kernel = new WebKernel(self::$projectDir);
        $this->assertInstanceOf(KernelInterface::class, $kernel);
        $this->assertInstanceOf(ContainerInterface::class, $container = $kernel->getContainer());
        $this->assertInstanceOf(EventDispatcherInterface::class, $container->get(EventDispatcherInterface::class));
        $this->assertEquals('test', $container->get('app.env'));
        $this->assertEquals('Europe/London', $container->get('app.timezone'));
        (new Filesystem())->remove(self::$projectDir . '/var/cache');
    }
}
