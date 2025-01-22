<?php

use Kuick\Framework\Listeners\EventLoggingListener;
use Kuick\Framework\Listeners\LocalizingListener;
use Kuick\Framework\Listeners\RequestHandlingListener;
use Kuick\Framework\Listeners\ResponseEmittingListener;
use Kuick\Framework\SystemCache;
use Kuick\Framework\SystemCacheInterface;
use Kuick\EventDispatcher\EventDispatcher;
use Kuick\EventDispatcher\ListenerProvider;
use Kuick\Framework\Api\Security\OpsGuard;
use Kuick\Framework\Api\UI\OpsController;
use Kuick\Http\Server\ExceptionHtmlRequestHandler;
use Kuick\Http\Server\ExceptionJsonRequestHandler;
use Kuick\Http\Server\ExceptionRequestHandlerInterface;
use Kuick\Routing\RoutingMiddleware;
use Kuick\Security\SecurityMiddleware;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

use function DI\autowire;

return [
    // interface to implementation mapping
    ExceptionRequestHandlerInterface::class => autowire(ExceptionJsonRequestHandler::class),
    ListenerProviderInterface::class => autowire(ListenerProvider::class),
    EventDispatcherInterface::class => autowire(EventDispatcher::class),
    SystemCacheInterface::class => autowire(SystemCache::class),

    // performance optimization: autowiring
    LocalizingListener::class => autowire(),
    EventLoggingListener::class => autowire(),
    RequestHandlingListener::class => autowire(),
    ResponseEmittingListener::class => autowire(),

    ExceptionHtmlRequestHandler::class => autowire(),
    RoutingMiddleware::class => autowire(),
    SecurityMiddleware::class => autowire(),
    OpsGuard::class => autowire(),
    OpsController::class => autowire(),
];