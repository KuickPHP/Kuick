<?php

namespace Tests\Kuick\Cache\Utils;

use Kuick\Cache\ArrayCache;
use Kuick\Cache\FileCache;
use Kuick\Cache\InvalidArgumentException;
use Kuick\Cache\RedisCache;
use Kuick\Cache\Utils\CacheFactory;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

use function PHPUnit\Framework\assertInstanceOf;

/**
 * @covers \Kuick\Cache\Utils\CacheFactory
 */
class CacheFactoryTest extends TestCase
{
    public function testIfFileCacheIsCreated(): void
    {
        $cache = (new CacheFactory())->create('file:///tmp');
        assertInstanceOf(FileCache::class, $cache);
    }

    public function testIfRedisCacheIsCreated(): void
    {
        $cache = (new CacheFactory())->create('redis://127.0.0.1');
        assertInstanceOf(RedisCache::class, $cache);
    }

    public function testIfArrayCacheIsCreated(): void
    {
        $cache = (new CacheFactory())->create('array://');
        assertInstanceOf(ArrayCache::class, $cache);
    }

    public function testIfExceptionIsThrownForInvalidDSN(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new CacheFactory())->create('inexistent://127.0.0.1');
    }
}
