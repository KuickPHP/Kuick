<?php

namespace Tests\Kuick\App\DIFactories;

use DI\ContainerBuilder;
use Kuick\App\DIFactories\AddDefinitions;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

/**
 * @covers \Kuick\App\DIFactories\AddDefinitions
 * @covers \Kuick\App\DIFactories\FactoryAbstract
 */
class AddDefinitionsTest extends TestCase
{
    public static string $projectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(dirname(__DIR__) . '/../../Mocks/MockProjectDir');
    }

    public function testIfDevDefinitionsAreLoaded(): void
    {
        $cb = new ContainerBuilder();
        (new AddDefinitions($cb))(self::$projectDir, 'dev');
        $container = $cb->build();
        assertEquals('Testing App', $container->get('kuick.app.name'));
        assertTrue($container->get('kuick.app.monolog.usemicroseconds'));
        assertEquals('WARNING', $container->get('kuick.app.monolog.level'));
        assertEquals('Europe/Warsaw', $container->get('kuick.app.timezone'));
    }

    public function testIfProdDefinitionsAreLoaded(): void
    {
        $cb = new ContainerBuilder();
        (new AddDefinitions($cb))(self::$projectDir, 'prod');
        $container = $cb->build();
        assertEquals('Testing App', $container->get('kuick.app.name'));
        assertFalse($container->get('kuick.app.monolog.usemicroseconds'));
        assertEquals('WARNING', $container->get('kuick.app.monolog.level'));
        assertEquals('Europe/Paris', $container->get('kuick.app.timezone'));
    }
}
