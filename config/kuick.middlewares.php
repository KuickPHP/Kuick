<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\App\Middleware;
use Kuick\App\MiddlewarePriority;
use Kuick\Http\Server\OptionsSendingMiddleware;
use Kuick\Http\Server\RoutingMiddleware;

return [
    // default 204 for OPTIONS    
    new Middleware(OptionsSendingMiddleware::class, MiddlewarePriority::PRIORITY_HIGHEST),
    // routing middleware by Kuick
    // @TODO: extract guard execution to the another middleware
    new Middleware(RoutingMiddleware::class, MiddlewarePriority::PRIORITY_LOWEST),
];