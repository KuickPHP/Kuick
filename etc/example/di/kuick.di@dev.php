<?php

/**
 * PHP-DI definitions
 * @see https://php-di.org/doc/php-definitions.html
 */
return [
    //no token for dev
    'kuick.app.ops.guards.token' => '',

    //debug for dev
    'kuick.app.monolog.level' => 'DEBUG',
    'kuick.app.monolog.useMicroseconds' => true,
    //more handlers
    'kuick.app.monolog.handlers' => [
        [
            'type' => 'stream',
            'path' => 'php://stdout',
        ],
        [
            'type' => 'stream',
            'path' => BASE_PATH . '/var/log/error.log',
            'level' => 'ERROR',
        ],
    ],
];