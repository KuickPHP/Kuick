<?php

namespace Kuick\Tests\App;

use DI\NotFoundException;
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
    private static string $projectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(dirname(__DIR__) . '/../Mocks/MockProjectDir');
    }

    /**
     * Needs to be run in separate process, cause emmiter sends headers
     * @runInSeparateProcess
     */
    public function testIfContainerIsRebuiltForDev(): void
    {
        #kuick.app.env = dev (from .env)
        $adcb = (new AppDIContainerBuilder());
        $container = $adcb(self::$projectDir);
        assertEquals('Testing App', $container->get('kuick.app.name'));
        assertEquals('Europe/Warsaw', $container->get('kuick.app.timezone'));
        assertEquals('local value', $container->get('example'));
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
        (new Filesystem())->remove(self::$projectDir . '/var/cache/prod');
        $uncachedContainer = (new AppDIContainerBuilder())(self::$projectDir);
        assertEquals('Testing App', $uncachedContainer->get('kuick.app.name'));
        assertEquals('Europe/Paris', $uncachedContainer->get('kuick.app.timezone'));
        assertEquals('local value', $uncachedContainer->get('example'));
        assertFalse($uncachedContainer->get('kuick.app.monolog.usemicroseconds'));
        assertEquals('INFO', $uncachedContainer->get('kuick.app.monolog.level'));
    }

    /**
     * Needs to be run in separate process, cause emmiter sends headers
     * @runInSeparateProcess
     */
    public function testIfContainerIsBuiltOnlyOnce(): void
    {
        putenv('KUICK_APP_ENV=prod');
        //cached container
        $cachedContainer = (new AppDIContainerBuilder())(self::$projectDir);
        assertEquals('Testing', $cachedContainer->has('kuick.app.name'));
        assertEquals('Europe/Paris', $cachedContainer->has('kuick.app.timezone'));
        assertFalse($cachedContainer->get('kuick.app.monolog.usemicroseconds'));
        assertEquals('INFO', $cachedContainer->get('kuick.app.monolog.level'));
    }

    /**
     * Needs to be run in separate process, cause emmiter sends headers
     * @runInSeparateProcess
     */
    public function testIfContainerBuildsOnEmptyDir(): void
    {
        $this->expectException(NotFoundException::class);
        //
        (new AppDIContainerBuilder())(self::$projectDir . '/empty');
    }
}
