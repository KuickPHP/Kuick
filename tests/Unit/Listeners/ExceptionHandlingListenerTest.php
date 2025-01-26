<?php

namespace Tests\Unit\Kuick\Framework\Listeners;

use Exception;
use Kuick\EventDispatcher\EventDispatcher;
use Kuick\EventDispatcher\ListenerProvider;
use Kuick\Framework\Events\ExceptionRaisedEvent;
use Kuick\Framework\Events\ResponseCreatedEvent;
use Kuick\Framework\Listeners\ExceptionHandlingListener;
use Kuick\Http\Server\JsonNotFoundRequestHandler;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use stdClass;

/**
 * @covers Kuick\Framework\Listeners\ExceptionHandlingListener
 */
class ExceptionHandlingListenerTest extends TestCase
{
    public function testIfExceptionHandlerProduces404Response(): void
    {
        $listenerProvider = new ListenerProvider();
        /**
         * @var \Psr\Http\Message\ResponseInterface $responseCreated
         */
        $responseCreated = null;
        $listenerProvider->registerListener(ResponseCreatedEvent::class, function (ResponseCreatedEvent $event) use (&$responseCreated) {
            $responseCreated = $event->getResponse();
        });
        $listener = new ExceptionHandlingListener(
            new EventDispatcher($listenerProvider),
            new JsonNotFoundRequestHandler(),
            new NullLogger()
        );

        $listener(new ExceptionRaisedEvent(new Exception('test')));
        $this->assertEquals(404, $responseCreated->getStatusCode());
    }
}
