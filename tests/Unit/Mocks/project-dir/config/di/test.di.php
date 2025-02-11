<?php

use function DI\env;

return [
    'app.name'      => env('APP_NAME', 'Testing App'),
    'app.charset'   => env('APP_CHARSET', 'UTF-8'),
    'app.locale'    => env('APP_LOCALE', 'en_US.utf-8'),
    'app.timezone'  => env('APP_TIMEZONE', 'UTC'),

    'app.log.usemicroseconds' => env('APP_LOG_USEMICROSECONDS', false),
    'app.log.level' => env('APP_LOG_LEVEL', 'WARNING'),
    'app.log.handlers' => [
        [
            'type' => 'stream',
        ],
    ],

    'example' => env('ONLY_LOCAL', ''),
];
