<?php

namespace Tests\Unit\App\DependencyInjection\Factories;

use DI\ContainerBuilder;
use Kuick\Framework\Config\ConfigException;
use Kuick\Framework\Config\ConfigIndexer;
use Kuick\Framework\DependencyInjection\Factories\ConsoleApplicationFactory;
use Kuick\Framework\SystemCache;
use Kuick\Framework\SystemCacheInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Console\Application;

/**
 * @covers Kuick\Framework\DependencyInjection\Factories\ConsoleApplicationFactory
 */
class ConsoleApplicationFactoryTest extends TestCase
{
    private static string $projectDir;
    private static string $invalidProjectDir;
    private static string $invalidProjectDir2;
    private static string $invalidProjectDir3;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(dirname(__DIR__) . '/../Mocks/project-dir');
        self::$invalidProjectDir = realpath(dirname(__DIR__) . '/../Mocks/invalid-project-dir');
        self::$invalidProjectDir2 = realpath(dirname(__DIR__) . '/../Mocks/invalid-project-dir-2');
        self::$invalidProjectDir3 = realpath(dirname(__DIR__) . '/../Mocks/invalid-project-dir-3');
    }

    public function testIfConsoleApplicationIsBuilt(): void
    {
        $builder = new ContainerBuilder();
        $builder->useAttributes(true);
        $builder->addDefinitions([
            SystemCacheInterface::class => new SystemCache(self::$projectDir, 'dev'),
            LoggerInterface::class => new NullLogger(),
            'app.name' => 'Testing App',
            'app.env' => 'dev',
            'app.projectDir' => self::$projectDir,
        ]);
        (new ConsoleApplicationFactory())->build($builder);
        $container = $builder->build();
        $application = $container->get(Application::class);
        // 1 command config file found
        $this->assertCount(1, $container->get(ConfigIndexer::class)->getConfigFilePaths(ConfigIndexer::COMMANDS_FILE_SUFFIX));
        $this->assertInstanceOf(Application::class, $application);
    }

    public function testIfConsoleApplicationRaisesExceptionForBrokenCommand(): void
    {
        $builder = new ContainerBuilder();
        $builder->useAttributes(true);
        $builder->addDefinitions([
            SystemCacheInterface::class => new SystemCache(self::$invalidProjectDir, 'dev'),
            LoggerInterface::class => new NullLogger(),
            'app.name' => 'Testing App',
            'app.env' => 'dev',
            'app.projectDir' => self::$invalidProjectDir,
        ]);
        (new ConsoleApplicationFactory())->build($builder);
        $this->expectException(ConfigException::class);
        $container = $builder->build();
        $container->get(Application::class);
    }

    public function testIfConsoleApplicationRaisesExceptionForAnotherBrokenCommand(): void
    {
        $builder = new ContainerBuilder();
        $builder->useAttributes(true);
        $builder->addDefinitions([
            SystemCacheInterface::class => new SystemCache(self::$invalidProjectDir, 'dev'),
            LoggerInterface::class => new NullLogger(),
            'app.name' => 'Testing App',
            'app.env' => 'dev',
            'app.projectDir' => self::$invalidProjectDir2,
        ]);
        (new ConsoleApplicationFactory())->build($builder);
        $this->expectException(ConfigException::class);
        $container = $builder->build();
        $container->get(Application::class);
    }

    public function testIfConsoleApplicationRaisesExceptionForYetAnotherBrokenCommands(): void
    {
        $builder = new ContainerBuilder();
        $builder->useAttributes(true);
        $builder->addDefinitions([
            SystemCacheInterface::class => new SystemCache(self::$invalidProjectDir, 'dev'),
            LoggerInterface::class => new NullLogger(),
            'app.name' => 'Testing App',
            'app.env' => 'dev',
            'app.projectDir' => self::$invalidProjectDir3,
        ]);
        (new ConsoleApplicationFactory())->build($builder);
        $this->expectException(ConfigException::class);
        $container = $builder->build();
        $container->get(Application::class);
    }
}
