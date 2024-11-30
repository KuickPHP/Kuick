<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

return [
    'kuick.app.name'      => 'Kuick App',
    'kuick.app.charset'   => 'UTF-8',
    'kuick.app.locale'    => 'en_US.utf-8',
    'kuick.app.timezone'  => 'UTC',

    'kuick.app.monolog.useMicroseconds' => false,
    'kuick.app.monolog.level' => 'WARNING',
    'kuick.app.monolog.handlers' => [
        [
            'type' => 'stream',
            'path' => 'php://stdout',
        ],
    ],

    'kuick.ops.guard.token' => 'secret-ops-token-please-change-me',
];