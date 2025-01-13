<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http\Server\Listeners;

use Kuick\Http\Server\RequestHandler;
use Kuick\Http\Server\Events\RequestReceived;
use Kuick\Http\Server\JsonMiddleware;

final class HandleRequestListener
{
    public function __construct(
        private JsonMiddleware $jsonMiddleware,
        private RequestHandler $requestHandler
    )
    {    
    }

    public function __invoke(RequestReceived $event): void
    {
        $event->setResponse($this->jsonMiddleware->process($event->getRequest(), $this->requestHandler));
    }
}