<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

use Kuick\Routing\RoutingMiddleware;
use Kuick\Security\SecurityMiddleware;

// middleware configuration
return [
    // security middleware
    SecurityMiddleware::class,
    // routing middleware
    RoutingMiddleware::class,
];