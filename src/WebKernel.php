<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework;

use Kuick\Framework\Events\KernelCreatedEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Web application Kernel
 */
final class WebKernel extends KernelAbstract
{
    public function __construct(string $projectDir)
    {
        parent::__construct($projectDir);

        // dispatching KernelCreatedEvent
        $this->getContainer()->get(EventDispatcherInterface::class)->dispatch(new KernelCreatedEvent($this));
    }
}
