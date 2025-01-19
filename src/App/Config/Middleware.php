<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Config;

class Middleware
{
    public function __construct(
        public readonly string $middleware,
        public readonly int $priority = MiddlewarePriority::PRIORITY_NORMAL,
    ) {
        new MiddlewareValidator($this);
    }
}
