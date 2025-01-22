<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Framework\Config;

use Kuick\EventDispatcher\ListenerPriority;

class ListenerConfig
{
    public function __construct(
        public readonly string $pattern,
        public readonly string $listenerClassName,
        public readonly int $priority = ListenerPriority::NORMAL,
    ) {
        new ListenerValidator($this);
    }
}
