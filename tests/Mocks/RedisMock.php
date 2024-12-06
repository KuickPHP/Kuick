<?php

namespace Tests\Kuick\Mocks;

use Kuick\Cache\Utils\RedisInterface;

class RedisMock implements RedisInterface
{
    private array $storage = [];
    private array $createTimes = [];
    private array $ttls = [];

    public function get(string $key): ?string
    {
        return $this->exists($key) ? $this->storage[$key] : null;
    }

    public function set(string $key, ?string $value = null, int $ttl = 0): bool
    {
        $this->storage[$key] = $value;
        $this->createTimes[$key] = time();
        $this->ttls[$key] = $ttl;
        return true;
    }

    public function del(string $key): bool
    {
        unset($this->createTimes[$key]);
        unset($this->storage[$key]);
        unset($this->ttls[$key]);
        return true;
    }

    public function flushDb(bool $sync = false): bool
    {
        $this->createTimes = $this->storage = $this->ttls = [];
        return true;
    }

    public function flushAll(bool $sync = false): bool
    {
        return $this->flushDb();
    }

    public function exists(string $key): bool
    {
        if (!array_key_exists($key, $this->ttls) ||
            !array_key_exists($key, $this->storage) ||
            !array_key_exists($key, $this->createTimes)) {
            return false;
        }
        $ttl = ($this->ttls[$key] == 0) ? 10000000 : $this->ttls[$key];
        //failed ttl
        if ((int) ($ttl + $this->createTimes[$key]) <= time()) {
            return false;
        }
        return $this->storage[$key];
    }

    public function keys(string $pattern = '*'): array
    {
        $keys = [];
        foreach (array_keys($this->storage) as $key) {
            if (!$this->exists($key)) {
                continue;
            }
            $keys[] = $key;
        }
        return $keys;
    }
}
