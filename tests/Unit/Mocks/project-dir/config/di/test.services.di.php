<?php

use Kuick\Framework\SystemCache;
use Kuick\Framework\SystemCacheInterface;
use Kuick\EventDispatcher\EventDispatcher;
use Kuick\EventDispatcher\ListenerProvider;
use Kuick\Http\Server\FallbackRequestHandlerInterface;
use Kuick\Http\Server\JsonNotFoundRequestHandler;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

use function DI\autowire;

return [
    // interface to implementation mapping
    FallbackRequestHandlerInterface::class => autowire(JsonNotFoundRequestHandler::class),
    ListenerProviderInterface::class => autowire(ListenerProvider::class),
    EventDispatcherInterface::class => autowire(EventDispatcher::class),
    SystemCacheInterface::class => autowire(SystemCache::class),
];
