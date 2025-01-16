<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\App\Listeners\CommandListener;
use Kuick\App\Listeners\LocalizationListener;
use Kuick\App\Listeners\LoggerListener;
use Kuick\App\Listeners\RequestListener;
use Kuick\App\Listeners\ResponseListener;
use Kuick\App\SystemCache;
use Kuick\App\SystemCacheInterface;
use Kuick\EventDispatcher\EventDispatcher;
use Kuick\EventDispatcher\ListenerProvider;
use Kuick\Ops\UI\OpsController;
use Kuick\Ops\Security\OpsGuard;
use Kuick\Http\Server\InvokableArgumentReflector;
use Kuick\Http\Server\JsonThrowableRequestHandler;
use Kuick\Http\Server\RoutingMiddleware;
use Kuick\Http\Server\StackRequestHandler;
use Kuick\Http\Server\ThrowableRequestHandlerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function DI\autowire;

return [
    // interface to implementation mapping
    SystemCacheInterface::class => autowire(SystemCache::class),
    ListenerProviderInterface::class => autowire(ListenerProvider::class),
    EventDispatcherInterface::class => autowire(EventDispatcher::class),
    RequestHandlerInterface::class => autowire(StackRequestHandler::class),
    ThrowableRequestHandlerInterface::class => autowire(JsonThrowableRequestHandler::class),

    // performance optimization: optional autowire definitions
    CommandListener::class => autowire(),
    LocalizationListener::class => autowire(),
    LoggerListener::class => autowire(),
    RequestListener::class => autowire(),
    ResponseListener::class => autowire(),

    RoutingMiddleware::class => autowire(),
    InvokableArgumentReflector::class => autowire(),
    OpsGuard::class => autowire(),
    OpsController::class => autowire(),
];