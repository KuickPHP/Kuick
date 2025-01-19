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
 * Guard definition
 */
class Guard
{
    public function __construct(
        public readonly string $path,
        public readonly string $guardClassName,
        public readonly array $methods = [
            RequestInterface::METHOD_GET,
            RequestInterface::METHOD_OPTIONS,
            RequestInterface::METHOD_POST,
            RequestInterface::METHOD_PUT,
            RequestInterface::METHOD_PATCH,
            RequestInterface::METHOD_DELETE,
        ],
    ) {
        // validate guard
        new GuardValidator($this);
    }
}
