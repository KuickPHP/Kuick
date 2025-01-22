<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\Listeners;

use Psr\Log\LoggerInterface;

final class EventLoggingListener
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function __invoke(object $event): void
    {
        $this->logger->info('Event triggered: ' . get_class($event));
    }
}
