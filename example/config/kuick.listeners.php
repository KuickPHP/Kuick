<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\App\Config\Listener;
use Kuick\App\Events\CommandReceived;
use Kuick\App\Events\KernelCreated;
use Kuick\App\Events\RequestReceived;
use Kuick\App\Events\ResponseCreated;
use Kuick\App\Listeners\CommandLaunchingListener;
use Kuick\App\Listeners\EventLoggingListener;
use Kuick\App\Listeners\LocalizingListener;
use Kuick\App\Listeners\RequestHandlingListener;
use Kuick\App\Listeners\ResponseEmittingListener;
use Kuick\EventDispatcher\ListenerPriority;

return [
    // logging all the events
    new Listener('*', EventLoggingListener::class, ListenerPriority::HIGH),
    // setup locale after kernel is created
    new Listener(KernelCreated::class, LocalizingListener::class),
    // handle request when request is received
    new Listener(RequestReceived::class, RequestHandlingListener::class),
    // emmit response when response is created
    new Listener(ResponseCreated::class, ResponseEmittingListener::class),
    // execute command when console command is arrived
    new Listener(CommandReceived::class, CommandLaunchingListener::class),
];
