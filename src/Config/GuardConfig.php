<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\Config;

use Kuick\Http\Message\RequestInterface;

/**
 * Guard definition
 */
final class GuardConfig
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
    }
}
