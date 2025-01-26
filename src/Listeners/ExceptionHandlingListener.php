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
use Kuick\Http\Server\FallbackRequestHandlerInterface;
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
        $exception = $exceptionRaisedEvent->getException();
        $this->eventDispatcher->dispatch(new ResponseCreatedEvent(
            $this->fallbackHandler->handleError($exception)
        ));
        $this->logger->error($exception->getMessage(), $exception->getTrace());
    }
}
