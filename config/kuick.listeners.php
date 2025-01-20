<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\App\Config\ListenerConfig;
use Kuick\App\Events\CommandReceivedEvent;
use Kuick\App\Events\KernelCreatedEvent;
use Kuick\App\Events\RequestReceivedEvent;
use Kuick\App\Events\ResponseCreatedEvent;
use Kuick\App\Listeners\CommandLaunchingListener;
use Kuick\App\Listeners\EventLoggingListener;
use Kuick\App\Listeners\LocalizingListener;
use Kuick\App\Listeners\RequestHandlingListener;
use Kuick\App\Listeners\ResponseEmittingListener;
use Kuick\EventDispatcher\ListenerPriority;

return [
    // logging all the events
    new ListenerConfig('*', EventLoggingListener::class, ListenerPriority::HIGH),
    // setup locale after kernel is created
    new ListenerConfig(KernelCreatedEvent::class, LocalizingListener::class),
    // handle request when received
    new ListenerConfig(RequestReceivedEvent::class, RequestHandlingListener::class),
    // emitt response when created
    new ListenerConfig(ResponseCreatedEvent::class, ResponseEmittingListener::class),
    // execute command when received
    new ListenerConfig(CommandReceivedEvent::class, CommandLaunchingListener::class),
];
