<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use Kuick\EventDispatcher\ListenerPriority;

class Listener
{
    public function __construct(
        public readonly string $pattern,
        public readonly string $callable,
        public readonly int $priority = ListenerPriority::NORMAL,
    ) {
        new ListenerValidator($this);
    }
}
