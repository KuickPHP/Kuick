<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\App\Config\Middleware;
use Kuick\App\Config\MiddlewarePriority;
use Kuick\Http\Server\OptionsSendingMiddleware;
use Kuick\Routing\RoutingMiddleware;
use Kuick\Security\SecurityMiddleware;

return [
    // default 204 for OPTIONS    
    new Middleware(OptionsSendingMiddleware::class, MiddlewarePriority::PRIORITY_HIGHEST),
    // security middleware
    new Middleware(SecurityMiddleware::class, MiddlewarePriority::PRIORITY_HIGHER),
    // routing middleware
    new Middleware(RoutingMiddleware::class, MiddlewarePriority::PRIORITY_LOWEST),
];