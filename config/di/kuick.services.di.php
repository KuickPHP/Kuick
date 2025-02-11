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
use Kuick\Framework\Api\UI\DocHtmlController;
use Kuick\Framework\Api\UI\DocJsonController;
use Kuick\Framework\Api\UI\OpsController;
use Kuick\Framework\DependencyInjection\ConfigIndexer;
use Kuick\Framework\Listeners\ExceptionHandlingListener;
use Kuick\Framework\Listeners\RegisteringPhpErrorHandlerListener;
use Kuick\Http\Server\FallbackRequestHandlerInterface;
use Kuick\Http\Server\JsonNotFoundRequestHandler;
use Kuick\Routing\RoutingMiddleware;
use Kuick\Security\SecurityMiddleware;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

use function DI\autowire;

return [
    // interface to implementation mapping
    FallbackRequestHandlerInterface::class => autowire(JsonNotFoundRequestHandler::class),
    ListenerProviderInterface::class => autowire(ListenerProvider::class),
    EventDispatcherInterface::class => autowire(EventDispatcher::class),
    SystemCacheInterface::class => autowire(SystemCache::class),
    ConfigIndexer::class => autowire(),

    // performance optimization: autowiring
    // listeners
    EventLoggingListener::class => autowire(),
    ExceptionHandlingListener::class => autowire(),
    LocalizingListener::class => autowire(),
    RegisteringPhpErrorHandlerListener::class => autowire(),
    RequestHandlingListener::class => autowire(),
    ResponseEmittingListener::class => autowire(),

    // middlewares
    RoutingMiddleware::class => autowire(),
    SecurityMiddleware::class => autowire(),

    // UI
    DocHtmlController::class => autowire(),
    DocJsonController::class => autowire(),
    OpsController::class => autowire(),
    OpsGuard::class => autowire(),
];