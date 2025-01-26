<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\Events;

use Throwable;

final class ExceptionRaisedEvent
{
    public function __construct(private Throwable $exception)
    {
    }

    public function getException(): Throwable
    {
        return $this->exception;
    }
}
