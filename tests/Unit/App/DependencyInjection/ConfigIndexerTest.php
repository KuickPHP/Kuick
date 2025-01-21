<?php

namespace Tests\Unit\App\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Kuick\App\DependencyInjection\ConfigIndexer;
use Kuick\App\SystemCache;
use Kuick\Cache\InMemoryCache;
use Psr\Log\NullLogger;

/**
 * @covers Kuick\App\DependencyInjection\ConfigIndexer
 */
class ConfigIndexerTest extends TestCase
{
    private static string $projectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(dirname(__DIR__) . '/../../Mocks/project-dir');
    }

    public function testIndexingRouteConfig(): void
    {
        $indexer = new ConfigIndexer(new SystemCache(self::$projectDir, 'dev'), new NullLogger());
        $routes = $indexer->getConfigFiles(self::$projectDir, 'routes');
        $this->assertCount(1, $routes);
    }

    public function testLoadingConfigFromCache(): void
    {
        $indexer = new ConfigIndexer(new SystemCache(self::$projectDir, 'prod'), new NullLogger());
        $routes = $indexer->getConfigFiles(self::$projectDir, 'routes');
        $this->assertCount(1, $routes);
        $cachedRoutes = $indexer->getConfigFiles(self::$projectDir, 'routes');
        $this->assertCount(1, $cachedRoutes);
    }
}
