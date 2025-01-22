<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\Listeners;

use Kuick\Framework\Events\RequestReceivedEvent;
use Kuick\Framework\Events\ResponseCreatedEvent;
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
