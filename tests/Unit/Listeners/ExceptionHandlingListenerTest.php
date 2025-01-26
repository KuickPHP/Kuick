<?php

namespace Tests\Unit\Kuick\Framework\Listeners;

use Kuick\EventDispatcher\EventDispatcher;
use Kuick\EventDispatcher\ListenerProvider;
use Kuick\Framework\Events\ResponseCreatedEvent;
use Kuick\Framework\Listeners\ExceptionHandlingListener;
use Kuick\Http\Server\JsonNotFoundRequestHandler;
use PHPUnit\Framework\TestCase;
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
        $listener = new ExceptionHandlingListener(new EventDispatcher($listenerProvider), new JsonNotFoundRequestHandler());

        $listener(new stdClass());
        $this->assertEquals(404, $responseCreated->getStatusCode());
    }
}
