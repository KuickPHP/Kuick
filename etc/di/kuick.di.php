<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

return [
    'kuick.app.name'      => 'Kuick App',
    'kuick.app.charset'   => 'UTF-8',
    'kuick.app.locale'    => 'en_US.utf-8',
    'kuick.app.timezone'  => 'UTC',

    'kuick.app.monolog.usemicroseconds' => false,
    'kuick.app.monolog.level' => 'WARNING',
    'kuick.app.monolog.handlers' => [
        [
            'type' => 'stream',
            'path' => 'php://stdout',
        ],
    ],

    //there is no valid token by default, you should provide one through environment variables
    'kuick.ops.guard.token' => '',
];