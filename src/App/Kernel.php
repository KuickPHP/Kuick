<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use Kuick\App\DependencyInjection\ContainerCreator;
use Kuick\App\Events\KernelCreated;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Application Kernel
 */
class Kernel
{
    public const APP_ENV = 'KUICK_APP_ENV';
    public const DI_APP_ENV_KEY = 'kuick.app.env';
    public const DI_PROJECT_DIR_KEY = 'kuick.app.projectDir';
    public const DI_LISTENERS_KEY = 'kuick.app.listeners';
    public const ENV_DEV = 'dev';
    public const ENV_PROD = 'prod';

    private ContainerInterface $container;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(public readonly string $projectDir)
    {
        //building DI container
        $this->container = (new ContainerCreator())($projectDir);
        $this->eventDispatcher = $this->container->get(EventDispatcherInterface::class);
        $listenerProvider = $this->container->get(ListenerProviderInterface::class);
        foreach ($this->container->get(self::DI_LISTENERS_KEY) as $listener) {
            $listenerProvider->registerListener($listener->pattern, $this->container->get($listener->callable), $listener->priority);
        }
        $this->eventDispatcher->dispatch(new KernelCreated($this));
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }
}
