<?php

namespace Tests\Unit\App\DependencyInjection;

use DI\ContainerBuilder;
use Kuick\App\Config\ConfigException;
use Kuick\App\DependencyInjection\ListenersBuilder;
use Kuick\App\Kernel;
use Kuick\App\KernelInterface;
use Kuick\App\SystemCache;
use Kuick\App\SystemCacheInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @covers \Kuick\App\DependencyInjection\ListenersBuilder
 */
class ListenersBuilderTest extends TestCase
{
    private static string $projectDir;
    private static string $invalidProjectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(dirname(__DIR__) . '/../../Mocks/project-dir');
        self::$invalidProjectDir = realpath(dirname(__DIR__) . '/../../Mocks/invalid-project-dir');
    }

    public function testIfListenersIsBuilt(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            SystemCacheInterface::class => new SystemCache(self::$projectDir, 'dev'),
            LoggerInterface::class => new NullLogger(),
            'kuick.app.projectDir' => self::$projectDir,
        ]);
        (new ListenersBuilder($builder))();
        $container = $builder->build();
        $this->assertCount(1, $container->get(KernelInterface::DI_LISTENERS_KEY));
    }

    public function testIfFailedConfigRaisesException(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            SystemCacheInterface::class => new SystemCache(self::$invalidProjectDir, 'dev'),
            LoggerInterface::class => new NullLogger(),
            'kuick.app.projectDir' => self::$invalidProjectDir,
        ]);
        $this->expectException(ConfigException::class);
        (new ListenersBuilder($builder))();
        $container = $builder->build();
        $container->get(KernelInterface::DI_LISTENERS_KEY);
    }
}
