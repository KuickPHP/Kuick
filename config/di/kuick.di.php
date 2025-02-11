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
    // some default values for Kuick Application
    'app.name'      => env('APP_NAME', 'Kuick App'),
    'app.charset'   => env('APP_CHARSET', 'UTF-8'),
    'app.locale'    => env('APP_LOCALE', 'en_US.utf-8'),
    'app.timezone'  => env('APP_TIMEZONE', 'UTC'),

    'app.log.level' => env('APP_LOG_LEVEL', 'WARNING'),
    'app.log.usemicroseconds' => env('APP_LOG_USEMICROSECONDS', false),
    // note that the first handler is "FingersCrossed", so after WARNING is raised
    // you will get all the logs from specified level and below
    'app.log.handlers' => [
        ['type' => 'fingersCrossed'],
        // remove the line above, and uncomment the line below to get a "standard" log to stdout
        // ['type' => 'stream', 'path' => 'php://stdout'],
    ],

    // there is no valid token by default, you should provide one through environment variables
    'api.ops.guard.token' => env('API_OPS_GUARD_TOKEN', ''),
];