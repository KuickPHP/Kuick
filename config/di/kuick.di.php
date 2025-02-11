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
    'kuick.app.name'      => env('KUICK_APP_NAME', 'Kuick App'),
    'kuick.app.charset'   => env('KUICK_APP_CHARSET', 'UTF-8'),
    'kuick.app.locale'    => env('KUICK_APP_LOCALE', 'en_US.utf-8'),
    'kuick.app.timezone'  => env('KUICK_APP_TIMEZONE', 'UTC'),

    'kuick.app.monolog.level' => env('KUICK_APP_MONOLOG_LEVEL', 'WARNING'),
    'kuick.app.monolog.usemicroseconds' => env('KUICK_APP_MONOLOG_USEMICROSECONDS', false),
    // note that the first handler is "FingersCrossed", so after WARNING is raised
    // you will get all the logs from specified level and below
    'kuick.app.monolog.handlers' => [
        ['type' => 'fingersCrossed'],
        // remove the line above, and uncomment the line below to get a "standard" log to stdout
        // ['type' => 'stream', 'path' => 'php://stdout'],
    ],

    // there is no valid token by default, you should provide one through environment variables
    'kuick.ops.guard.token' => env('KUICK_OPS_GUARD_TOKEN', ''),
];