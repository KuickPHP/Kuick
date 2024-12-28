<?php

namespace Kuick\Tests\Mocks;

use Psr\Container\ContainerInterface;

class ContainerMock implements ContainerInterface
{
    public function __construct(private array $items = [])
    {
    }

    public function get(string $key): mixed
    {
        return $this->items[$key] ?? null;
    }

    public function set(string $key, mixed $value): self
    {
        $this->items[$key] = $value;
        return $this;
    }

    public function has(string $key): bool
    {
        return isset($this->items[$key]);
    }
}
