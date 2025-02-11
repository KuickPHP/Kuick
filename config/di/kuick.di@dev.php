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
    'api.ops.guard.token' => env('API_OPS_GUARD_TOKEN', 'let-me-in'),

    //debug for dev
    'app.log.level' => env('APP_LOG_LEVEL', 'DEBUG'),
    'app.log.usemicroseconds' => env('APP_LOG_USEMICROSECONDS', true),
];