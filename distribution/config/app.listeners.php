<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\Framework\Config\ListenerConfig;
use Kuick\Framework\Events\KernelCreatedEvent;
use Kuick\Framework\Events\RequestReceivedEvent;
use Kuick\Framework\Events\ResponseCreatedEvent;
use Kuick\Framework\Listeners\EventLoggingListener;
use Kuick\Framework\Listeners\LocalizingListener;
use Kuick\Framework\Listeners\RequestHandlingListener;
use Kuick\Framework\Listeners\ResponseEmittingListener;
use Kuick\EventDispatcher\ListenerPriority;

return [
    // logging all the events
    new ListenerConfig('*', EventLoggingListener::class, ListenerPriority::HIGHEST),
    // setup locale after kernel is created
    new ListenerConfig(KernelCreatedEvent::class, LocalizingListener::class),
    // handle request when received
    new ListenerConfig(RequestReceivedEvent::class, RequestHandlingListener::class),
    // emitt response when created
    new ListenerConfig(ResponseCreatedEvent::class, ResponseEmittingListener::class),
];
