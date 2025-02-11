<?php

use function DI\env;

return [
    'kuick.app.name'      => env('KUICK_APP_NAME', 'Testing App'),
    'kuick.app.charset'   => env('KUICK_APP_CHARSET', 'UTF-8'),
    'kuick.app.locale'    => env('KUICK_APP_LOCALE', 'en_US.utf-8'),
    'kuick.app.timezone'  => env('KUICK_APP_TIMEZONE', 'UTC'),

    'kuick.app.monolog.usemicroseconds' => env('KUICK_APP_MONOLOG_USEMICROSECONDS', false),
    'kuick.app.monolog.level' => env('KUICK_APP_MONOLOG_LEVEL', 'WARNING'),
    'kuick.app.monolog.handlers' => [
        [
            'type' => 'stream',
        ],
    ],

    'example' => env('ONLY_LOCAL', ''),
];
