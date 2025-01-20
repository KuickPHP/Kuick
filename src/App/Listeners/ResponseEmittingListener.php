<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Listeners;

use Kuick\App\Events\ResponseCreatedEvent;
use Kuick\Http\Server\ResponseEmitter;

final class ResponseEmittingListener
{
    public function __invoke(ResponseCreatedEvent $event): void
    {
        // emmit response
        (new ResponseEmitter())($event->getResponse());
    }
}
