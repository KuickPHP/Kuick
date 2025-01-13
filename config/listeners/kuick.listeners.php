<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\App\Events\KernelCreated;
use Kuick\App\Listeners\LoggerListener;
use Kuick\App\Listeners\SetupLocaleListener;
use Kuick\Event\ListenerPriority;
use Kuick\Http\Server\Events\RequestReceived;
use Kuick\Http\Server\Events\ResponseCreated;
use Kuick\Http\Server\Listeners\EmmitResponseListener;
use Kuick\Http\Server\Listeners\HandleRequestListener;

return [
    [
        'pattern' => KernelCreated::class,
        'listener' => SetupLocaleListener::class,
    ],
    [
        'pattern' => RequestReceived::class,
        'listener' => HandleRequestListener::class,
    ],
    [
        'pattern' => ResponseCreated::class,
        'listener' => EmmitResponseListener::class,
    ],
    [
        'pattern' => '*',
        'listener' => LoggerListener::class,
        'priority' => ListenerPriority::HIGH,
    ]
];