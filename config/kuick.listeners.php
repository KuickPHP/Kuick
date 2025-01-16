<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\App\Events\CommandReceived;
use Kuick\App\Events\KernelCreated;
use Kuick\App\Events\RequestReceived;
use Kuick\App\Events\ResponseCreated;
use Kuick\App\Listener;
use Kuick\App\Listeners\CommandListener;
use Kuick\App\Listeners\LocalizationListener;
use Kuick\App\Listeners\LoggerListener;
use Kuick\App\Listeners\RequestListener;
use Kuick\App\Listeners\ResponseListener;
use Kuick\EventDispatcher\ListenerPriority;

return [
    // log all events
    new Listener('*', LoggerListener::class, ListenerPriority::HIGH),
    // setup locale after kernel is created
    new Listener(KernelCreated::class, LocalizationListener::class),
    // handle request when request is received
    new Listener(RequestReceived::class, RequestListener::class),
    // emmit response when response is created
    new Listener(ResponseCreated::class, ResponseListener::class),
    // execute command when console command is arrived
    new Listener(CommandReceived::class, CommandListener::class),
];
