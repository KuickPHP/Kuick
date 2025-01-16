<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Listeners;

use Psr\Log\LoggerInterface;

class LoggerListener
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function __invoke(object $event): void
    {
        $this->logger->info('Event triggered: ' . get_class($event));
    }
}