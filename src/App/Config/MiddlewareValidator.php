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
    public function __construct(Middleware $middleware) {
        if (!class_exists($middleware->middleware)) {
            throw new ConfigException('Middleware "' . $middleware->middleware . '" does not exist.');
        }
        foreach (class_implements($middleware->middleware) as $interface) {
            if ($interface === MiddlewareInterface::class) {
                return;
            }
        }
        throw new ConfigException('Middleware "' . $middleware->middleware . '" must implement MiddlewareInterface.');
    }
}
