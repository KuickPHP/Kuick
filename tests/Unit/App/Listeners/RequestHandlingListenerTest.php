<?php

namespace Kuick\Tests\App\Listeners;

use Kuick\App\Events\RequestReceivedEvent;
use Kuick\App\Events\ResponseCreatedEvent;
use Kuick\App\Listeners\RequestHandlingListener;
use Kuick\EventDispatcher\EventDispatcher;
use Kuick\EventDispatcher\ListenerProvider;
use Kuick\Tests\Mocks\MockRequestHandler;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kuick\App\Listeners\RequestHandlingListener
 */
class RequestHandlingListenerTest extends TestCase
{
    public function testIfRequestIsHandled(): void
    {
        $listenerProvider = new ListenerProvider();
        /**
         * @var \Psr\Http\Message\ResponseInterface $responseCreated
         */
        $responseCreated = null;
        $listenerProvider->registerListener(ResponseCreatedEvent::class, function (ResponseCreatedEvent $event) use (&$responseCreated) {
            $responseCreated = $event->getResponse();
        });
        $requestHandling = new RequestHandlingListener(new MockRequestHandler(), new EventDispatcher($listenerProvider));

        $requestReceivedEvent = new RequestReceivedEvent(new ServerRequest('GET', '/test'));
        $requestHandling($requestReceivedEvent);
        $this->assertEquals(200, $responseCreated->getStatusCode());
    }
}
