<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\Config;

use Closure;

/**
 * Command definition
 */
class CommandConfig
{
    public function __construct(
        public readonly string $name,
        public readonly string|Closure $command,
        public readonly string $description = '',
    ) {
    }
}
