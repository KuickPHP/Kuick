<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\DependencyInjection;

use DI\ContainerBuilder;
use Kuick\App\Listeners\CommandLaunchingListener;
use Kuick\App\Listeners\EventLoggingListener;
use Kuick\App\Listeners\LocalizingListener;
use Kuick\App\Listeners\RequestHandlingListener;
use Kuick\App\Listeners\ResponseEmittingListener;
use Kuick\App\SystemCache;
use Kuick\App\SystemCacheInterface;
use Kuick\EventDispatcher\EventDispatcher;
use Kuick\EventDispatcher\ListenerProvider;
use Kuick\Ops\Security\OpsGuard;
use Kuick\Ops\UI\OpsController;
use Kuick\Routing\RoutingMiddleware;
use Kuick\Security\SecurityMiddleware;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

use function DI\autowire;

class ServiceBuilder
{
    public function __construct(private ContainerBuilder $builder)
    {
    }
    public function __invoke()
    {
        $this->builder->addDefinitions([
            ListenerProviderInterface::class => autowire(ListenerProvider::class),
            EventDispatcherInterface::class => autowire(EventDispatcher::class),        
            SystemCacheInterface::class => autowire(SystemCache::class),

            // performance optimization: autowiring
            CommandLaunchingListener::class => autowire(),
            LocalizingListener::class => autowire(),
            EventLoggingListener::class => autowire(),
            RequestHandlingListener::class => autowire(),
            ResponseEmittingListener::class => autowire(),

            RoutingMiddleware::class => autowire(),
            SecurityMiddleware::class => autowire(),
            OpsGuard::class => autowire(),
            OpsController::class => autowire(),
        ]);
    }
}
