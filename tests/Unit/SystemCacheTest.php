<?php

namespace Tests\Unit\Kuick\Framework;

use Kuick\Framework\SystemCache;
use Kuick\Cache\LayeredCache;
use PHPUnit\Framework\TestCase;

/**
 * @covers Kuick\Framework\SystemCache
 */
class SystemCacheTest extends TestCase
{
    public function testIfProdCacheServiceIsWellDefined(): void
    {
        $cache = new SystemCache(__DIR__ . '/Mocks/project-dir', 'prod');
        $this->assertInstanceOf(LayeredCache::class, $cache);
    }

    public function testIfDevCacheIsCreated(): void
    {
        $cache = new SystemCache(dirname(__DIR__) . 'even-inexistent-dir-will-work', 'dev');
        $this->assertInstanceOf(LayeredCache::class, $cache);
    }
}
