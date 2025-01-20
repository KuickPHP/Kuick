<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Config;

use Psr\Http\Server\MiddlewareInterface;

class MiddlewareValidator
{
    public function __construct(MiddlewareConfig $middlewareConfig)
    {
        if (!class_exists($middlewareConfig->middleware)) {
            throw new ConfigException('Middleware "' . $middlewareConfig->middleware . '" does not exist.');
        }
        foreach (class_implements($middlewareConfig->middleware) as $interface) {
            if ($interface === MiddlewareInterface::class) {
                return;
            }
        }
        throw new ConfigException('Middleware "' . $middlewareConfig->middleware . '" must implement MiddlewareInterface.');
    }
}
