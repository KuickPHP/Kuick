<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\Framework\Middlewares\OptionsSendingMiddleware;
use Kuick\Routing\RoutingMiddleware;
use Kuick\Security\SecurityMiddleware;

return [
    // default 204 for OPTIONS    
    OptionsSendingMiddleware::class,
    // security middleware
    SecurityMiddleware::class,
    // routing middleware
    RoutingMiddleware::class,
];