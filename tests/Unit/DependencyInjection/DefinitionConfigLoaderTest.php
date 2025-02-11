<?php

namespace Tests\Unit\App\DependencyInjection;

use DI\ContainerBuilder;
use PHPUnit\Framework\TestCase;
use Kuick\Framework\DependencyInjection\DefinitionConfigLoader;

/**
 * @covers Kuick\Framework\DependencyInjection\DefinitionConfigLoader
 */
class DefinitionConfigLoaderTest extends TestCase
{
    private static string $projectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(dirname(__DIR__) . '/Mocks/project-dir');
    }

    public function testLoadConfig(): void
    {
        $builder = new ContainerBuilder();

        $loader = new DefinitionConfigLoader($builder);
        $loader(self::$projectDir, 'dev');

        $container = $builder->build();
        $this->assertEquals('Testing App', $container->get('app.name'));
        $this->assertEquals('vendor.value', $container->get('vendor.key'));
        $this->assertEquals('WARNING', $container->get('app.log.level'));
        $this->assertEquals('Europe/Warsaw', $container->get('app.timezone'));
    }
}
