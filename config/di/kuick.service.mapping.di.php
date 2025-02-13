<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

use Kuick\Framework\SystemCache;
use Kuick\Framework\SystemCacheInterface;
use Kuick\EventDispatcher\EventDispatcher;
use Kuick\EventDispatcher\ListenerProvider;
use Kuick\Http\Server\JsonNotFoundRequestHandler;
use Kuick\Http\Server\StackRequestHandler;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Console\Application;

use function DI\autowire;
use function DI\create;

// interface to implementation mapping
return [
    Application::class => autowire(Application::class),
    EventDispatcherInterface::class => autowire(EventDispatcher::class),
    ListenerProviderInterface::class => autowire(ListenerProvider::class),
    RequestHandlerInterface::class => create(StackRequestHandler::class)
        ->constructor(
            create(JsonNotFoundRequestHandler::class)
        ),
    SystemCacheInterface::class => autowire(SystemCache::class),
];
