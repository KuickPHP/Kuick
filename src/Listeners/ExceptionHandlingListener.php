<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\Listeners;

use Kuick\EventDispatcher\EventDispatcher;
use Kuick\Framework\Events\ExceptionRaisedEvent;
use Kuick\Framework\Events\ResponseCreatedEvent;
use Kuick\Http\Message\Response;
use Kuick\Http\Server\FallbackRequestHandlerInterface;
use Nyholm\Psr7\ServerRequest;
use Psr\Log\LoggerInterface;

final class ExceptionHandlingListener
{
    public function __construct(
        private EventDispatcher $eventDispatcher,
        private FallbackRequestHandlerInterface $fallbackHandler,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(ExceptionRaisedEvent $exceptionRaisedEvent): void
    {
        $errorResponse = $this->fallbackHandler->handle(new ServerRequest('GET', '/'))
            ->withStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
        $this->eventDispatcher->dispatch(new ResponseCreatedEvent($errorResponse));
        $this->logger->error(
            $exceptionRaisedEvent->getException()->getMessage(),
            $exceptionRaisedEvent->getException()->getTrace()
        );
    }
}
