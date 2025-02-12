<?php

namespace Tests\Unit\App\DependencyInjection\Factories;

use DI\ContainerBuilder;
use Kuick\Framework\Config\ConfigException;
use Kuick\Framework\DependencyInjection\Factories\ListenerListFactory;
use Kuick\Framework\KernelInterface;
use Kuick\Framework\SystemCache;
use Kuick\Framework\SystemCacheInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @covers Kuick\Framework\DependencyInjection\Factories\ListenerListFactory
 */
class ListenerListFactoryTest extends TestCase
{
    private static string $projectDir;
    private static string $invalidProjectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(dirname(__DIR__) . '/../Mocks/project-dir');
        self::$invalidProjectDir = realpath(dirname(__DIR__) . '/../Mocks/invalid-project-dir');
    }

    public function testIfListenerListIsBuilt(): void
    {
        $builder = new ContainerBuilder();
        $builder->useAttributes(true);
        $builder->addDefinitions([
            SystemCacheInterface::class => new SystemCache(self::$projectDir, 'dev'),
            LoggerInterface::class => new NullLogger(),
            'app.env' => 'dev',
            'app.projectDir' => self::$projectDir,
        ]);
        (new ListenerListFactory())->build($builder);
        $container = $builder->build();
        $this->assertCount(1, $container->get(KernelInterface::DI_LISTENERS_KEY));
    }

    public function testIfFailedConfigRaisesException(): void
    {
        $builder = new ContainerBuilder();
        $builder->useAttributes(true);
        $builder->addDefinitions([
            SystemCacheInterface::class => new SystemCache(self::$invalidProjectDir, 'dev'),
            LoggerInterface::class => new NullLogger(),
            'app.env' => 'dev',
            'app.projectDir' => self::$invalidProjectDir,
        ]);
        $this->expectException(ConfigException::class);
        (new ListenerListFactory())->build($builder);
        $container = $builder->build();
        $container->get(KernelInterface::DI_LISTENERS_KEY);
    }
}
