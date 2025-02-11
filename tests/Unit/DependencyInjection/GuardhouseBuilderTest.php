<?php

namespace Tests\Unit\App\DependencyInjection;

use DI\ContainerBuilder;
use Kuick\Framework\Config\ConfigException;
use Kuick\Framework\DependencyInjection\GuardhouseBuilder;
use Kuick\Framework\SystemCache;
use Kuick\Framework\SystemCacheInterface;
use Kuick\Security\Guardhouse;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @covers Kuick\Framework\DependencyInjection\GuardhouseBuilder
 */
class GuardhouseBuilderTest extends TestCase
{
    private static string $projectDir;
    private static string $invalidProjectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(dirname(__DIR__) . '/Mocks/project-dir');
        self::$invalidProjectDir = realpath(dirname(__DIR__) . '/Mocks/invalid-project-dir');
    }

    public function testIfGuardhouseIsBuilt(): void
    {
        $builder = new ContainerBuilder();
        $builder->useAttributes(true);
        $builder->addDefinitions([
            SystemCacheInterface::class => new SystemCache(self::$projectDir, 'dev'),
            LoggerInterface::class => new NullLogger(),
            'app.env' => 'dev',
            'app.projectDir' => self::$projectDir,
        ]);
        (new GuardhouseBuilder($builder))();
        $container = $builder->build();
        $this->assertInstanceOf(Guardhouse::class, $container->get(Guardhouse::class));
    }

    public function testIfGuardhouseRaisesExceptionForBrokenRoutes(): void
    {
        $builder = new ContainerBuilder();
        $builder->useAttributes(true);
        $builder->addDefinitions([
            SystemCacheInterface::class => new SystemCache(self::$invalidProjectDir, 'dev'),
            LoggerInterface::class => new NullLogger(),
            'app.env' => 'dev',
            'app.projectDir' => self::$invalidProjectDir,
        ]);
        (new GuardhouseBuilder($builder))();
        $this->expectException(ConfigException::class);
        $container = $builder->build();
        $this->assertInstanceOf(Guardhouse::class, $container->get(Guardhouse::class));
    }
}
