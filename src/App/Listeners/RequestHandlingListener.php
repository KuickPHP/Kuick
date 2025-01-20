<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Listeners;

use Kuick\App\Events\RequestReceivedEvent;
use Kuick\App\Events\ResponseCreatedEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RequestHandlingListener
{
    public function __construct(
        private RequestHandlerInterface $requestHandler,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(RequestReceivedEvent $requestReceivedEvent): void
    {
        $response = $this->requestHandler->handle($requestReceivedEvent->getRequest());
        $this->eventDispatcher->dispatch(new ResponseCreatedEvent($response));
    }
}
