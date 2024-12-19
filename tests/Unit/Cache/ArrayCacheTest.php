<?php

namespace Tests\Kuick\Cache;

use Kuick\Cache\ArrayCache;
use PHPUnit\Framework\TestCase;
use Tests\Kuick\Mocks\RedisMock;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertTrue;

/**
 * @covers \Kuick\Cache\ArrayCache
 */
class ArrayCacheTest extends TestCase
{
    public function testIfCacheCanBeSetAndGet(): void
    {
        $rc = new ArrayCache();
        assertNull($rc->get('inexistent-key'));
        assertFalse($rc->has('inexistent-key'));
        assertTrue($rc->set('/my/key', 'test-value'));
        assertTrue($rc->has('/my/key'));
        assertEquals('test-value', $rc->get('/my/key'));
    }

    public function testIfCacheCanBeOverwritten(): void
    {
        $rc = new ArrayCache();
        assertTrue($rc->set('foo', 'bar'));
        assertEquals('bar', $rc->get('foo'));
        assertTrue($rc->set('foo', 'baz'));
        assertEquals('baz', $rc->get('foo'));
    }

    public function testIfCacheCanBeDeleted(): void
    {
        $rc = new ArrayCache();
        assertTrue($rc->set('foo', 'bar'));
        assertEquals('bar', $rc->get('foo'));
        assertTrue($rc->delete('foo'));
        assertNull($rc->get('foo'));
    }

    public function testIfExpiredCacheReturnsNull(): void
    {
        $rc = new ArrayCache();
        $rc->set('foo', 'bar', 1);
        assertEquals('bar', $rc->get('foo'));
        sleep(1);
        assertNull($rc->get('foo'));
    }

    public function testMultipleSetsAndGetsDeletes(): void
    {
        $rc = new ArrayCache();
        $sourceArray = [
            'first' => 'first value',
            'second' => 'second value',
            'third' => 'third value',
        ];
        $rc->setMultiple($sourceArray);
        assertEquals($sourceArray, $rc->getMultiple(['first', 'second', 'third']));
        assertTrue($rc->deleteMultiple(['second', 'third']));
        assertEquals(['first' => 'first value'], $rc->getMultiple(['first']));
    }

    public function testClear(): void
    {
        $rc = new ArrayCache();
        $rc->set('first', 'first value');
        $rc->setMultiple([
            'foo' => 'baz',
            'baz' => 'bar',
        ]);
        assertTrue($rc->has('foo'));
        assertTrue($rc->has('first'));
        assertTrue($rc->has('baz'));
        assertTrue($rc->clear());
        assertFalse($rc->has('foo'));
        assertFalse($rc->has('first'));
        assertFalse($rc->has('baz'));
    }
}
