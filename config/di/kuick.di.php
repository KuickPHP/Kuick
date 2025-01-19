<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use function DI\env;

return [
    'kuick.app.name'      => env('KUICK_APP_NAME', 'Kuick App'),
    'kuick.app.charset'   => env('KUICK_APP_CHARSET', 'UTF-8'),
    'kuick.app.locale'    => env('KUICK_APP_LOCALE', 'en_US.utf-8'),
    'kuick.app.timezone'  => env('KUICK_APP_TIMEZONE', 'UTC'),

    'kuick.app.monolog.usemicroseconds' => env('KUICK_APP_MONOLOG_USEMICROSECONDS', false),
    'kuick.app.monolog.level' => env('KUICK_APP_MONOLOG_LEVEL', 'WARNING'),
    'kuick.app.monolog.handlers' => [
        [
            'type' => 'fingersCrossed',
        ],
    ],

    // there is no valid token by default, you should provide one through environment variables
    'kuick.ops.guard.token' => env('KUICK_OPS_GUARD_TOKEN', ''),
];