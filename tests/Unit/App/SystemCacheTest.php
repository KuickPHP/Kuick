<?php

namespace Kuick\Tests\App;

use Kuick\App\SystemCache;
use Kuick\Cache\LayeredCache;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kuick\App\SystemCache
 */
class SystemCacheTest extends TestCase
{
    public function testIfProdCacheServiceIsWellDefined(): void
    {
        $cache = new SystemCache(dirname(__DIR__) . '/../Mocks/project-dir', 'prod');
        $this->assertInstanceOf(LayeredCache::class, $cache);
    }

    public function testIfDevCacheIsCreated(): void
    {
        $cache = new SystemCache(dirname(__DIR__) . 'even-inexistent-dir-will-work', 'dev');
        $this->assertInstanceOf(LayeredCache::class, $cache);
    }
}
