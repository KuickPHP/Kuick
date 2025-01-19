<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Config;

use Kuick\Http\Message\RequestInterface;

/**
 * Route definition
 */
class RouteConfig
{
    public function __construct(
        public readonly string $path,
        public readonly string $controllerClassName,
        public readonly array $methods = [RequestInterface::METHOD_GET],
    ) {
        // validate route
        new RouteValidator($this);
    }
}
