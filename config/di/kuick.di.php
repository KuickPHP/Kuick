<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

use function DI\env;

return [
    // the default settings for Kuick Application
    'app.name'      => env('APP_NAME', 'Kuick App'),
    'app.charset'   => env('APP_CHARSET', 'UTF-8'),
    'app.locale'    => env('APP_LOCALE', 'en_US.utf-8'),
    'app.timezone'  => env('APP_TIMEZONE', 'UTC'),

    // log settings
    'app.log.level' => env('APP_LOG_LEVEL', 'NOTICE'),
    // setting this option to true is not recommended on production
    'app.log.usemicroseconds' => env('APP_LOG_USEMICROSECONDS', false),
    // log to standard output
    'app.log.handlers' => [
        ['type' => 'stream', 'path' => 'php://stdout'],
    ],

    // there is no valid token by default, you should provide one through environment variables
    'api.security.ops.guard.token' => env('API_SECURITY_OPS_GUARD_TOKEN', ''),
];
