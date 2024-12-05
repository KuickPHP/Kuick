<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Cache\Utils;

interface RedisInterface
{
    public function set(string $key, ?string $value = null, int $ttl = 0): bool;

    public function get(string $key): ?string;

    public function exists(string $key): bool;

    public function del(string $key): bool;

    public function flushDb(bool $sync = false): bool;

    public function flushAll(bool $sync = false): bool;

    public function keys(string $pattern = '*'): array;
}
