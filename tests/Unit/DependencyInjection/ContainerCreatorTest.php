<?php

namespace Kuick\Tests\App\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Kuick\Framework\DependencyInjection\ContainerCreator;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @covers Kuick\Framework\DependencyInjection\ContainerCreator
 */
class ContainerCreatorTest extends TestCase
{
    private static string $projectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(dirname(__DIR__) . '/Mocks/project-dir');
    }

    /**
     * Needs to be run in separate process
     * @runInSeparateProcess
     */
    public function testIfDevContainerIsBuiltForDev(): void
    {
        putenv('APP_ENV=dev');
        $container = (new ContainerCreator())->create(self::$projectDir);
        $this->assertEquals('Testing App', $container->get('app.name'));
        $this->assertEquals('dev', $container->get('app.env'));
        $this->assertEquals(self::$projectDir, $container->get('app.projectDir'));
        $this->assertIsArray($container->get('app.listeners'));
        $this->assertEquals('Testing App', $container->get('app.name'));
        $this->assertEquals('Europe/Warsaw', $container->get('app.timezone'));
    }

    /**
     * Needs to be run in separate process
     * @runInSeparateProcess
     */
    public function testIfProdContainerIsBuiltForProd(): void
    {
        // prod should be set by default
        (new Filesystem())->remove(self::$projectDir . '/var/cache');

        $uncachedContainer = (new ContainerCreator())->create(self::$projectDir);
        $this->assertEquals('Testing App', $uncachedContainer->get('app.name'));
        $this->assertEquals('Europe/Paris', $uncachedContainer->get('app.timezone'));
    }

    /**
     * Needs to be run in separate process
     * @runInSeparateProcess
     * @depends testIfProdContainerIsBuiltForProd
     */
    public function testIfProdContainerIsCachedForProd(): void
    {
        putenv('APP_ENV=prod');
        $uncachedContainer = (new ContainerCreator())->create(self::$projectDir);
        $this->assertEquals('Testing App', $uncachedContainer->get('app.name'));
        (new Filesystem())->remove(self::$projectDir . '/var/cache');
    }
}
