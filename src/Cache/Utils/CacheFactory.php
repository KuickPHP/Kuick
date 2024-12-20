<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick.git
 * @copyright  Copyright (c) 2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Cache\Utils;

use Kuick\Cache\ArrayCache;
use Kuick\Cache\FileCache;
use Kuick\Cache\InvalidArgumentException;
use Kuick\Cache\RedisCache;
use Nyholm\Dsn\DsnParser;
use Psr\SimpleCache\CacheInterface;

class CacheFactory
{
    private const REDIS_SCHEME = 'redis';
    private const FILE_SCHEME = 'file';
    private const ARRAY_SCHEME = 'array';

    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(string $dsnString): CacheInterface
    {
        $dsn = DsnParser::parse($dsnString);
        switch ($dsn->getScheme()) {
            case self::REDIS_SCHEME:
                $redisClient = (new RedisClientFactory())->__invoke($dsnString);
                return new RedisCache($redisClient);
            case self::FILE_SCHEME:
                return new FileCache($dsn->getPath());
            case self::ARRAY_SCHEME:
                return new ArrayCache();
        }
        throw new InvalidArgumentException('Cache backend invalid: should be one of redis, file, array');
    }
}
