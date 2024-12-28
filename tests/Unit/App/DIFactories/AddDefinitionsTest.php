<?php

namespace Kuick\Tests\App\DIFactories;

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
        $builder = new ContainerBuilder();
        (new AddDefinitions($builder))(self::$projectDir, 'dev');
        $container = $builder->build();
        assertEquals('Testing App', $container->get('kuick.app.name'));
        assertTrue($container->get('kuick.app.monolog.usemicroseconds'));
        assertEquals('WARNING', $container->get('kuick.app.monolog.level'));
        assertEquals('Europe/Warsaw', $container->get('kuick.app.timezone'));
    }

    public function testIfProdDefinitionsAreLoaded(): void
    {
        $builder = new ContainerBuilder();
        (new AddDefinitions($builder))(self::$projectDir, 'prod');
        $container = $builder->build();
        assertEquals('Testing App', $container->get('kuick.app.name'));
        assertFalse($container->get('kuick.app.monolog.usemicroseconds'));
        assertEquals('WARNING', $container->get('kuick.app.monolog.level'));
        assertEquals('Europe/Paris', $container->get('kuick.app.timezone'));
    }
}
