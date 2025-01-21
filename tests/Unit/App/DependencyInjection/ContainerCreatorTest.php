<?php

namespace Kuick\Tests\App\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Kuick\Framework\DependencyInjection\ContainerCreator;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @covers \Kuick\Framework\DependencyInjection\ContainerCreator
 */
class ContainerCreatorTest extends TestCase
{
    private static string $projectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(dirname(__DIR__) . '/../Mocks/project-dir');
    }

    /**
     * Needs to be run in separate process
     * @runInSeparateProcess
     */
    public function testIfDevContainerIsBuiltForDev(): void
    {
        putenv('KUICK_APP_ENV=dev');
        (new Filesystem())->remove(self::$projectDir . '/var/cache');
        $containerCreator = new ContainerCreator();
        $container = $containerCreator(self::$projectDir);
        $this->assertEquals('Testing App', $container->get('kuick.app.name'));
        $this->assertEquals('dev', $container->get('kuick.app.env'));
        $this->assertEquals(self::$projectDir, $container->get('kuick.app.projectDir'));
        $this->assertIsArray($container->get('kuick.app.listeners'));
        $this->assertEquals('Testing App', $container->get('kuick.app.name'));
        $this->assertEquals('Europe/Warsaw', $container->get('kuick.app.timezone'));
    }

    /**
     * Needs to be run in separate process
     * @runInSeparateProcess
     */
    public function testIfProdContainerIsBuiltForProd(): void
    {
        putenv('KUICK_APP_ENV=prod');
        (new Filesystem())->remove(self::$projectDir . '/var/cache');
        $containerCreator = new ContainerCreator();

        $uncachedContainer = $containerCreator(self::$projectDir);
        $this->assertEquals('Testing App', $uncachedContainer->get('kuick.app.name'));
        $this->assertEquals('Europe/Paris', $uncachedContainer->get('kuick.app.timezone'));
    }

    /**
     * Needs to be run in separate process
     * @runInSeparateProcess
     * @depends testIfProdContainerIsBuiltForProd
     */
    public function testIfProdContainerIsCachedForProd(): void
    {
        putenv('KUICK_APP_ENV=prod');
        $containerCreator = new ContainerCreator();
        $uncachedContainer = $containerCreator(self::$projectDir);
        $this->assertEquals('Testing App', $uncachedContainer->get('kuick.app.name'));
    }
}
