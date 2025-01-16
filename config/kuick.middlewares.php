<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\Http\Server\Middleware;
use Kuick\Http\Server\RoutingMiddleware;

return [
    new Middleware(RoutingMiddleware::class, Middleware::PRIORITY_NORMAL),
];