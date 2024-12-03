<?php

namespace Tests\Kuick\App;

use Kuick\App\AppDIContainerBuilder;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;

/**
 * @covers \Kuick\App\AppDIContainerBuilder
 */
class AppDIContainerBuilderTest extends TestCase
{
    /**
     * Needs to be run in separate process, cause emmiter sends headers
     * @runInSeparateProcess
     */
    public function testIfContainerIsRebuiltForDev(): void
    {
        $adcb = (new AppDIContainerBuilder());
        $container = $adcb(dirname(__DIR__) . '/../Mocks/FakeRoot');
        assertEquals('Testing', $container->has('kuick.app.name'));
        assertEquals('Europe/Warsaw', $container->has('kuick.app.timezone'));
        assertFalse($container->get('kuick.app.monolog.usemicroseconds'));
        assertEquals('INFO', $container->get('kuick.app.monolog.level'));
    }

    /**
     * Needs to be run in separate process, cause emmiter sends headers
     * @runInSeparateProcess
     */
    public function testIfProdContainerUtilizesProdConfigs(): void
    {
        putenv('KUICK_APP_ENV=prod');
        //real build
        (new AppDIContainerBuilder());
        //container from cache
        $adcb = (new AppDIContainerBuilder());
        $container = $adcb(dirname(__DIR__) . '/../Mocks/FakeRoot');
        assertEquals('Testing', $container->has('kuick.app.name'));
        assertEquals('Europe/Warsaw', $container->has('kuick.app.timezone'));
        assertFalse($container->get('kuick.app.monolog.usemicroseconds'));
        assertEquals('INFO', $container->get('kuick.app.monolog.level'));
    }
}
