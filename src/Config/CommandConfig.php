<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\Config;

/**
 * Command definition
 */
final class CommandConfig
{
    public function __construct(
        public readonly string $name,
        public readonly string $commandClassName,
        public readonly string $description = '',
    ) {
    }
}
