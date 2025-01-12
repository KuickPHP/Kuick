<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http\Server\Events;

use Throwable;

final class ResponseExceptionCatched
{
    public function __construct(private Throwable $exception)
    {
    }

    public function getException(): Throwable
    {
        return $this->exception;
    }
}