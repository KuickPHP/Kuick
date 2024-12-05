<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick.git
 * @copyright  Copyright (c) 2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Cache\Utils;

use Nyholm\Dsn\DsnParser;
use Redis;

class RedisClientFactory
{
    private const DEFAULT_PORT = 6379;

    public function __invoke(string $dsnString): Redis|RedisInterface
    {
        $dsn = DsnParser::parse($dsnString);
        //calculate options
        $options = [
            'host'           => $dsn->getHost(),
            'port'           => $dsn->getPort() ?? self::DEFAULT_PORT,
            'readTimeout'    => $dsn->getParameter('readTimeout', 2.5),
            'connectTimeout' => $dsn->getParameter('connectTimeout', 2.5),
            'retryInterval'  => $dsn->getParameter('retryInterval', 100),
            'persistent'     => $dsn->getParameter('persistent', true),
        ];
        $redis = new Redis($options);
        //optional database selection
        if ($dsn->getParameter('database')) {
            $redis->select($dsn->getParameter('database'));
        }
        //optional authentication
        if ($dsn->getParameter('user') || $dsn->getParameter('pass')) {
            $redis->auth(['user' => $dsn->getParameter('user', null), 'pass' => $dsn->getParameter('pass', null)]);
        }
        return $redis;
    }
}
