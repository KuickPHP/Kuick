<?php

namespace Tests\Unit\App\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Kuick\Framework\DependencyInjection\ConfigIndexer;
use Kuick\Framework\SystemCache;
use Kuick\Cache\InMemoryCache;
use Kuick\Framework\Config\ConfigException;
use Kuick\Framework\Config\RouteValidator;
use Psr\Log\NullLogger;

/**
 * @covers Kuick\Framework\DependencyInjection\ConfigIndexer
 */
class ConfigIndexerTest extends TestCase
{
    private static string $projectDir;
    private static string $invalidProjectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(dirname(__DIR__) . '/Mocks/project-dir');
        self::$invalidProjectDir = realpath(dirname(__DIR__) . '/Mocks/invalid-project-dir');
    }

    public function testIndexingRouteConfig(): void
    {
        $indexer = new ConfigIndexer(self::$projectDir, new SystemCache(self::$projectDir, 'dev'), new NullLogger());
        $routes = $indexer->getConfigFiles('routes', new RouteValidator());
        $this->assertCount(1, $routes);
    }

    public function testLoadingConfigFromCache(): void
    {
        $indexer = new ConfigIndexer(self::$projectDir, new SystemCache(self::$projectDir, 'prod'), new NullLogger());
        $routes = $indexer->getConfigFiles('routes', new RouteValidator());
        $this->assertCount(1, $routes);
        $cachedRoutes = $indexer->getConfigFiles('routes', new RouteValidator());
        $this->assertCount(1, $cachedRoutes);
    }

    public function testIfNotArrayConfigFileRaisesException(): void
    {
        $indexer = new ConfigIndexer(self::$invalidProjectDir, new SystemCache(self::$projectDir, 'prod'), new NullLogger());
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('must return an array');
        $indexer->getConfigFiles('routes2', new RouteValidator());
    }

    public function testIfInvalidObjectRaisesException(): void
    {
        $indexer = new ConfigIndexer(self::$invalidProjectDir, new SystemCache(self::$projectDir, 'prod'), new NullLogger());
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('One or more config items is not an object');
        $indexer->getConfigFiles('routes3', new RouteValidator());
    }
}
