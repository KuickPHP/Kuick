<?php

namespace Tests\Kuick\App;

use Kuick\App\AppDIContainerBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

/**
 * @covers \Kuick\App\AppDIContainerBuilder
 */
class AppDIContainerBuilderTest extends TestCase
{
    public static string $projectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(dirname(__DIR__) . '/../Mocks/MockProjectDir');
        $cacheDir = self::$projectDir . '/var/cache';
        $fs = new Filesystem();
        $fs->remove($cacheDir);
        $fs->mkdir($cacheDir);
    }

    /**
     * Needs to be run in separate process, cause emmiter sends headers
     * @runInSeparateProcess
     */
    public function testIfContainerIsRebuiltForDev(): void
    {
        $adcb = (new AppDIContainerBuilder());
        $container = $adcb(self::$projectDir);
        assertEquals('Testing', $container->has('kuick.app.name'));
        assertEquals('Europe/Warsaw', $container->has('kuick.app.timezone'));
        assertTrue($container->get('kuick.app.monolog.usemicroseconds'));
        assertEquals('INFO', $container->get('kuick.app.monolog.level'));
    }

    /**
     * Needs to be run in separate process, cause emmiter sends headers
     * @runInSeparateProcess
     */
    public function testIfProdContainerUtilizesProdConfigs(): void
    {
        putenv('KUICK_APP_ENV=prod');
        //uncached build
        $uncachedContainer = (new AppDIContainerBuilder())(self::$projectDir);
        assertEquals('Testing', $uncachedContainer->has('kuick.app.name'));
        //container loaded from cache
        $cachedContainer = (new AppDIContainerBuilder())(self::$projectDir);
        $cachedContainer = (new AppDIContainerBuilder())(self::$projectDir);
        assertEquals('Testing', $cachedContainer->has('kuick.app.name'));
        assertEquals('Europe/Warsaw', $cachedContainer->has('kuick.app.timezone'));
        assertFalse($cachedContainer->get('kuick.app.monolog.usemicroseconds'));
        assertEquals('INFO', $cachedContainer->get('kuick.app.monolog.level'));
    }
}
