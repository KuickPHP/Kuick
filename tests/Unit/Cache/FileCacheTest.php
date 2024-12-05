<?php

namespace Tests\Kuick\Cache;

use Kuick\Cache\CacheException;
use Kuick\Cache\FileCache;
use Kuick\Cache\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertTrue;

/**
 * @covers \Kuick\Cache\FileCache
 */
class FileCacheTest extends TestCase
{
    private static string $cacheDir;

    public static function setUpBeforeClass(): void
    {
        self::$cacheDir = dirname(__DIR__) . '/../Mocks/MockProjectDir/var/cache/test-cache';
        $fs = new Filesystem();
        $fs->remove(self::$cacheDir);
    }

    public function testIfCacheCanBeSetAndGet(): void
    {
        $fc = new FileCache(self::$cacheDir);
        assertNull($fc->get('inexistent-key'));
        assertFalse($fc->has('inexistent-key'));
        assertTrue($fc->set('/my/key', 'test-value'));
        assertTrue($fc->has('/my/key'));
        assertEquals('test-value', $fc->get('/my/key'));
    }

    public function testIfCacheCanBeOverwritten(): void
    {
        $fc = new FileCache(self::$cacheDir);
        assertTrue($fc->set('foo', 'bar'));
        assertEquals('bar', $fc->get('foo'));
        assertTrue($fc->set('foo', 'baz'));
        assertEquals('baz', $fc->get('foo'));
    }

    public function testIfCacheCanBeDeleted(): void
    {
        $fc = new FileCache(self::$cacheDir);
        assertTrue($fc->set('foo', 'bar'));
        assertEquals('bar', $fc->get('foo'));
        assertTrue($fc->delete('foo'));
        assertNull($fc->get('foo'));
    }

    public function testIfExpiredCacheReturnsNull(): void
    {
        $fc = new FileCache(self::$cacheDir);
        $fc->set('foo', 'bar', 1);
        assertEquals('bar', $fc->get('foo'));
        sleep(1);
        assertNull($fc->get('foo'));
    }

    public function testMultipleSetsAndGetsDeletes(): void
    {
        $fc = new FileCache(self::$cacheDir);
        $sourceArray = [
            'first' => 'first value',
            'second' => 'second value',
            'third' => 'third value',
        ];
        $fc->setMultiple($sourceArray);
        assertEquals($sourceArray, $fc->getMultiple(['first', 'second', 'third']));
        assertTrue($fc->deleteMultiple(['second', 'third']));
        assertEquals(['first' => 'first value'], $fc->getMultiple(['first']));
    }

    public function testClear(): void
    {
        $fc = new FileCache(self::$cacheDir);
        $fc->set('first', 'first value');
        $fc->setMultiple([
            'foo' => 'baz',
            'baz' => 'bar',
        ]);
        assertTrue($fc->has('foo'));
        assertTrue($fc->has('first'));
        assertTrue($fc->has('baz'));
        assertTrue($fc->clear());
        assertFalse($fc->has('foo'));
        assertFalse($fc->has('first'));
        assertFalse($fc->has('baz'));
    }

    public function testIfSetToInvalidDirectoryThrowsException(): void
    {
        file_put_contents(self::$cacheDir . '/not-a-dir', 'some content');
        $this->expectException(CacheException::class);
        $fc = new FileCache(self::$cacheDir . '/not-a-dir');
    }

    public function testIfKeyToShortThrowsException(): void
    {
        $fc = new FileCache(self::$cacheDir);
        //key to short
        $this->expectException(InvalidArgumentException::class);
        $fc->set('', 'bar');
    }

    public function testIfKeyTooLongThrowsException(): void
    {
        $fc = new FileCache(self::$cacheDir);
        $this->expectException(InvalidArgumentException::class);
        $fc->set('255+character-key-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'bar');
    }
}
