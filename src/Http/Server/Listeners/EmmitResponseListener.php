<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http\Server\Listeners;

use Kuick\Http\Server\Events\ResponseCreated;
use Kuick\Http\Server\ResponseEmitter;

final class EmmitResponseListener
{
    public function __invoke(ResponseCreated $event): void
    {
        // emmit response
        (new ResponseEmitter())($event->getResponse());
    }
}