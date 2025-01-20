<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Events;

use Kuick\App\Kernel;

final class CommandReceived
{
    public function __construct(private Kernel $kernel)
    {
    }

    public function getKernel(): Kernel
    {
        return $this->kernel;
    }
}
