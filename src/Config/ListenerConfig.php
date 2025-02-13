<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\Config;

use Kuick\EventDispatcher\ListenerPriority;

final class ListenerConfig
{
    public function __construct(
        public readonly string $pattern,
        public readonly string $listenerClassName,
        public readonly int $priority = ListenerPriority::NORMAL,
    ) {
    }
}
