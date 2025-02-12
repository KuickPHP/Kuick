<?php

namespace Tests\Unit\App\Config;

use PHPUnit\Framework\TestCase;
use Kuick\Framework\SystemCache;
use Kuick\Framework\Config\ConfigException;
use Kuick\Framework\Config\ConfigIndexer;
use Psr\Log\NullLogger;

/**
 * @covers Kuick\Framework\Config\ConfigIndexer
 */
class ConfigIndexerTest extends TestCase
{
    private static string $projectDir;
    private static string $invalidProjectDir;
    private static string $invalidProjectDir2;
    private static string $invalidProjectDir3;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(dirname(__DIR__) . '/Mocks/project-dir');
        self::$invalidProjectDir = realpath(dirname(__DIR__) . '/Mocks/invalid-project-dir');
        self::$invalidProjectDir2 = realpath(dirname(__DIR__) . '/Mocks/invalid-project-dir-2');
        self::$invalidProjectDir3 = realpath(dirname(__DIR__) . '/Mocks/invalid-project-dir-3');
    }

    public function testIndexingRouteConfigForDevEnvironment(): void
    {
        $indexer = new ConfigIndexer(self::$projectDir, 'dev', new SystemCache(self::$projectDir, 'dev'), new NullLogger());
        $this->assertCount(1, $indexer->getConfigFilePaths('commands'));
        $this->assertCount(1, $indexer->getConfigFilePaths('guards'));
        $this->assertCount(1, $indexer->getConfigFilePaths('listeners'));
        $this->assertCount(2, $indexer->getConfigFilePaths('routes'));
    }

    public function testLoadingConfigFromCache(): void
    {
        $indexer = new ConfigIndexer(self::$projectDir, 'prod', new SystemCache(self::$projectDir, 'prod'), new NullLogger());
        $routes = $indexer->getConfigFilePaths('routes');
        $this->assertCount(1, $routes);
        $cachedRoutes = $indexer->getConfigFilePaths('routes');
        $this->assertCount(1, $cachedRoutes);
    }

    public function testIfInvalidFileRaisesException(): void
    {
        $indexer = new ConfigIndexer(self::$projectDir, 'dev', new SystemCache(self::$projectDir, 'dev'), new NullLogger());
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Unknown config file key: invalid-key');
        $indexer->getConfigFilePaths('invalid-key');
    }

    public function testIfInvalidObjectRaisesException(): void
    {
        $indexer = new ConfigIndexer(self::$invalidProjectDir, 'dev', new SystemCache(self::$projectDir, 'dev'), new NullLogger());
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Config item is not an object');
        $indexer->getConfigFilePaths('routes');
    }

    public function testIfNotReturningArrayConfigRaisesException(): void
    {
        $indexer = new ConfigIndexer(self::$invalidProjectDir2, 'dev', new SystemCache(self::$projectDir, 'dev'), new NullLogger());
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('must return an array');
        $indexer->getConfigFilePaths('routes');
    }
    public function testIfStdClassRaisesExceptionOfMismatchedImplementation(): void
    {
        $indexer = new ConfigIndexer(self::$invalidProjectDir3, 'dev', new SystemCache(self::$projectDir, 'dev'), new NullLogger());
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Config item is not a: Kuick\Framework\Config\RouteConfig');
        $indexer->getConfigFilePaths('routes');
    }
}
