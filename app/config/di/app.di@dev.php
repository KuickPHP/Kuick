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
    // use precise time in logs (not advised for production)
    'kuick.app.monolog.usemicroseconds' => env('KUICK_APP_MONOLOG_USEMICROSECONDS', true),
    // debug for dev purposes
    'kuick.app.monolog.level' => env('KUICK_APP_MONOLOG_LEVEL', 'INFO'),

    // simple token for dev purposes
    'kuick.ops.guard.token' => env('KUICK_OPS_GUARD_TOKEN', 'let-me-in'),
];