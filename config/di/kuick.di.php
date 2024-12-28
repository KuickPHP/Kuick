<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\Ops\UI\OpsController;
use Kuick\Ops\Security\OpsGuard;
use Kuick\Http\Server\ActionHandler;

use function DI\autowire;
use function DI\env;

return [
    'kuick.app.name'      => env('KUICK_APP_NAME', 'Kuick App'),
    'kuick.app.charset'   => env('KUICK_APP_CHARSET', 'UTF-8'),
    'kuick.app.locale'    => env('KUICK_APP_LOCALE', 'en_US.utf-8'),
    'kuick.app.timezone'  => env('KUICK_APP_TIMEZONE', 'UTC'),

    'kuick.app.monolog.usemicroseconds' => env('KUICK_APP_MONOLOG_USEMICROSECONDS', false),
    'kuick.app.monolog.level' => env('KUICK_APP_MONOLOG_LEVEL', 'NOTICE'),
    'kuick.app.monolog.handlers' => [
        [
            'type' => 'fingersCrossed',
        ],
    ],

    //there is no valid token by default, you should provide one through environment variables
    'kuick.ops.guard.token' => env('KUICK_OPS_GUARD_TOKEN', ''),

    //performance optimization: optional autowire definitions
    ActionHandler::class => autowire(),
    OpsGuard::class => autowire(),
    OpsController::class => autowire(),
];