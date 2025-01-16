<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http\Server;

class Middleware
{
    public const PRIORITY_LOWEST = PHP_INT_MIN;
    public const PRIORITY_LOWER = -1000;
    public const PRIORITY_LOW = -100;
    public const PRIORITY_NORMAL = 0;
    public const PRIORITY_HIGH = 100;
    public const PRIORITY_HIGHER = 1000;
    public const PRIORITY_HIGHEST = PHP_INT_MAX;

    public function __construct(
        public readonly string $middleware,
        public readonly int $priority = self::PRIORITY_NORMAL,
    ) {
        new MiddlewareValidator($this);
    }
}
