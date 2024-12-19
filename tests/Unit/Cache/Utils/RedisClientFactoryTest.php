<?php

namespace Tests\Kuick\Cache\Utils;

use Kuick\Cache\Utils\RedisClientFactory;
use PHPUnit\Framework\TestCase;
use Redis;
use RedisException;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertInstanceOf;

/**
 * @covers \Kuick\Cache\Utils\RedisClientFactory
 */
class RedisClientFactoryTest extends TestCase
{
    public function testIfConfigIsWorkingCorrectly(): void
    {
        $client = (new RedisClientFactory())->create('redis://127.0.0.1:6379?persistent=false');
        assertInstanceOf(Redis::class, $client);
    }

    public function testIfWrongRedisHostThrowsAnExceptionWithGivenDatabase(): void
    {
        $this->expectException(RedisException::class);
        (new RedisClientFactory())->create('redis://some.inexistent.host:7000/1?persistent=false');
    }

    public function testIfAuthorizationTakesPlace(): void
    {
        $this->expectException(RedisException::class);
        (new RedisClientFactory())->create('redis://127.0.0.1:6379?user=user&pass=pass');
    }
}
