<?php

namespace Tests\Kuick\SimpleCache;

use DateInterval;
use Kuick\SimpleCache\CacheValueSerializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertStringMatchesFormat;
use function PHPUnit\Framework\assertTrue;

/**
 * @covers \Kuick\SimpleCache\CacheValueSerializer
 */
class CacheValueSerializerTest extends TestCase
{
    private static string $cacheDir;

    public static function setUpBeforeClass(): void
    {
        self::$cacheDir = dirname(__DIR__) . '/../Mocks/FakeRoot/var/cache/test-cache';
        $fs = new Filesystem();
        $fs->remove(self::$cacheDir);
    }

    public function testIfSerializationWorksBothWays(): void
    {
        $cvs = new CacheValueSerializer();
        $serializedValue = $cvs->serialize('test', new DateInterval('PT3600S'));
        assertEquals('test', $cvs->unserialize($serializedValue));
        $anotherSerializedValue = $cvs->serialize('another');
        assertEquals('another', $cvs->unserialize($anotherSerializedValue));
    }

    public function testIfExpiredValueReturnsNull(): void
    {
        $cvs = new CacheValueSerializer();
        $serializedValue = $cvs->serialize('test', 1);
        sleep(1);
        assertNull($cvs->unserialize($serializedValue));
    }
}
