<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Listeners;

use Kuick\App\Events\RequestReceived;
use Kuick\App\Events\ResponseCreated;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RequestListener
{
    public function __construct(
        private RequestHandlerInterface $requestHandler,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(RequestReceived $event): void
    {
        $response = $this->requestHandler->handle($event->getRequest());
        $this->eventDispatcher->dispatch(new ResponseCreated($response));
    }
}
