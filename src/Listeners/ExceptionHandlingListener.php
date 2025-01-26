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
use Nyholm\Psr7\ServerRequest;

final class ExceptionHandlingListener
{
    public function __construct(
        private EventDispatcher $eventDispatcher,
        private FallbackRequestHandlerInterface $fallbackHandler
    ) {
    }

    public function __invoke(ExceptionRaisedEvent $exceptionRaisedEvent): void
    {
        $this->eventDispatcher->dispatch(new ResponseCreatedEvent(
            $this->fallbackHandler->handle(new ServerRequest('GET', '/'))
        ));
        //re-throw the original exception
        throw $exceptionRaisedEvent->getException();
    }
}
