<?php

namespace Tests\Unit\Kuick\Framework\Mocks;

use Kuick\EventDispatcher\EventDispatcher;
use Kuick\EventDispatcher\ListenerProvider;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class MockContainer implements ContainerInterface
{
    public function __construct(private array $items = [])
    {
        $this->set(EventDispatcherInterface::class, new EventDispatcher(new ListenerProvider()));
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
