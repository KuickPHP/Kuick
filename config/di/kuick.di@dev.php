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
    //simple token for dev
    'kuick.ops.guard.token' => env('KUICK_OPS_GUARD_TOKEN', 'let-me-in'),

    //debug for dev
    'kuick.app.monolog.level' => env('KUICK_APP_MONOLOG_LEVEL', 'DEBUG'),
    'kuick.app.monolog.usemicroseconds' => env('KUICK_APP_MONOLOG_USEMICROSECONDS', true),
];