<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\DependencyInjection;

use DI\ContainerBuilder;
use Kuick\App\SystemCache;
use Kuick\App\SystemCacheInterface;
use Kuick\EventDispatcher\EventDispatcher;
use Kuick\EventDispatcher\ListenerProvider;
use Kuick\Http\Server\ExceptionJsonRequestHandler;
use Kuick\Http\Server\ExceptionRequestHandlerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

use function DI\autowire;

class ServiceImplementationMapper
{
    public function __construct(private ContainerBuilder $builder)
    {
    }

    public function __invoke()
    {
        $this->builder->addDefinitions([
            ExceptionRequestHandlerInterface::class => autowire(ExceptionJsonRequestHandler::class),
            ListenerProviderInterface::class => autowire(ListenerProvider::class),
            EventDispatcherInterface::class => autowire(EventDispatcher::class),
            SystemCacheInterface::class => autowire(SystemCache::class),
        ]);
    }
}
