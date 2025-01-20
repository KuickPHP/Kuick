<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Events;

use Psr\Http\Message\ServerRequestInterface;

final class RequestReceivedEvent
{
    public function __construct(private ServerRequestInterface $request)
    {
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}
