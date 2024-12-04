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
    private string $projectDir;

    protected function setUp(): void
    {
        $this->projectDir = realpath(dirname(__DIR__) . '/../Mocks/MockProjectDir');
        $cacheDir = $this->projectDir . '/var/cache';
        $fs = new Filesystem();
        $fs->remove($cacheDir);
    }

    /**
     * Needs to be run in separate process, cause emmiter sends headers
     * @runInSeparateProcess
     */
    public function testIfContainerIsRebuiltForDev(): void
    {
        $adcb = (new AppDIContainerBuilder());
        $container = $adcb($this->projectDir);
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
        $uncachedContainer = (new AppDIContainerBuilder())($this->projectDir);
        assertEquals('Testing', $uncachedContainer->has('kuick.app.name'));
        //container loaded from cache
        $cachedContainer = (new AppDIContainerBuilder())($this->projectDir);
        assertEquals('Testing', $cachedContainer->has('kuick.app.name'));
        assertEquals('Europe/Warsaw', $cachedContainer->has('kuick.app.timezone'));
        assertFalse($cachedContainer->get('kuick.app.monolog.usemicroseconds'));
        assertEquals('INFO', $cachedContainer->get('kuick.app.monolog.level'));
    }
}
