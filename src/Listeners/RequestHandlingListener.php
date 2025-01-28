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
use Psr\Log\LoggerInterface;

final class RequestHandlingListener
{
    public function __construct(
        private RequestHandlerInterface $requestHandler,
        private EventDispatcherInterface $eventDispatcher,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(RequestReceivedEvent $requestReceivedEvent): void
    {
        $request = $requestReceivedEvent->getRequest();
        $this->logger->info('Request received:', [
            'uri' => $request->getUri()->getPath(),
            'method' => $request->getMethod(),
        ]);
        $response = $this->requestHandler->handle($request);
        $this->eventDispatcher->dispatch(new ResponseCreatedEvent($response));
    }
}
