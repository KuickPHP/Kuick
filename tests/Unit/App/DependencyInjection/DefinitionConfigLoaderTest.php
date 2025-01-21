<?php

namespace Tests\Unit\App\DependencyInjection;

use DI\ContainerBuilder;
use PHPUnit\Framework\TestCase;
use Kuick\App\DependencyInjection\DefinitionConfigLoader;

/**
 * @covers \Kuick\App\DependencyInjection\DefinitionConfigLoader
 */
class DefinitionConfigLoaderTest extends TestCase
{
    private static string $projectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(dirname(__DIR__) . '/../../Mocks/project-dir');
    }

    public function testLoadConfig()
    {
        $builder = new ContainerBuilder();

        $loader = new DefinitionConfigLoader($builder);
        $loader(self::$projectDir, 'dev');

        $container = $builder->build();
        $this->assertEquals('Testing App', $container->get('kuick.app.name'));
        $this->assertEquals('vendor.value', $container->get('vendor.key'));
        $this->assertEquals('WARNING', $container->get('kuick.app.monolog.level'));
        $this->assertEquals('Europe/Warsaw', $container->get('kuick.app.timezone'));
    }
}
