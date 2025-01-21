<?php

namespace Kuick\Tests\App\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Kuick\App\DependencyInjection\ContainerCreator;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @covers \Kuick\App\DependencyInjection\ContainerCreator
 */
class ContainerCreatorTest extends TestCase
{
    private static string $projectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(dirname(__DIR__) . '/../../Mocks/project-dir');
        (new Filesystem())->remove(self::$projectDir . '/var/cache');
    }

    // public function testIfDevContainerCreatorIsWellDefined(): void
    // {
    //     putenv('KUICK_APP_ENV=dev');
    //     $containerCreator = new ContainerCreator();
    //     $container = $containerCreator(self::$projectDir);
    //     $this->assertEquals('Testing App', $container->get('kuick.app.name'));
    //     $this->assertEquals('dev', $container->get('kuick.app.env'));
    //     $this->assertEquals(self::$projectDir, $container->get('kuick.app.projectDir'));
    //     $this->assertIsArray($container->get('kuick.app.listeners'));
    // }

    public function testIfProdContainerIsBuiltForProd(): void
    {
        putenv('KUICK_APP_ENV=prod');
        $containerCreator = new ContainerCreator();

        $uncachedContainer = $containerCreator(self::$projectDir);
        $this->assertEquals('Testing App', $uncachedContainer->get('kuick.app.name'));
    }

    public function testIfProdContainerIsCachedForProd(): void
    {
        putenv('KUICK_APP_ENV=prod');
        $containerCreator = new ContainerCreator();
        $cachedContainer = $containerCreator(self::$projectDir);
        $this->assertEquals('Testing App', $cachedContainer->get('kuick.app.name'));
    }
}
