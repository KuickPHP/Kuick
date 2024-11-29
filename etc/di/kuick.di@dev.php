<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

return [
    //let-me-in token for dev
    'kuick.app.ops.guards.token' => 'let-me-in',

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