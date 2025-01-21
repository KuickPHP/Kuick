<?php

use Kuick\App\Listeners\CommandLaunchingListener;
use Kuick\App\Listeners\EventLoggingListener;
use Kuick\App\Listeners\LocalizingListener;
use Kuick\App\Listeners\RequestHandlingListener;
use Kuick\App\Listeners\ResponseEmittingListener;
use Kuick\App\SystemCache;
use Kuick\App\SystemCacheInterface;
use Kuick\EventDispatcher\EventDispatcher;
use Kuick\EventDispatcher\ListenerProvider;
use Kuick\Example\Console\PingCommand;
use Kuick\Example\UI\PingController;
use Kuick\Http\Server\ExceptionHtmlRequestHandler;
use Kuick\Http\Server\ExceptionJsonRequestHandler;
use Kuick\Http\Server\ExceptionRequestHandlerInterface;
use Kuick\Ops\Security\OpsGuard;
use Kuick\Ops\UI\OpsController;
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
    CommandLaunchingListener::class => autowire(),
    LocalizingListener::class => autowire(),
    EventLoggingListener::class => autowire(),
    RequestHandlingListener::class => autowire(),
    ResponseEmittingListener::class => autowire(),

    ExceptionHtmlRequestHandler::class => autowire(),
    RoutingMiddleware::class => autowire(),
    SecurityMiddleware::class => autowire(),
    PingCommand::class => autowire(),
    PingController::class => autowire(),
    OpsGuard::class => autowire(),
    OpsController::class => autowire(),
];
