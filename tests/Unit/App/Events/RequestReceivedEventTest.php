<?php

namespace Kuick\Tests\App\Events;

use Kuick\App\Events\RequestReceivedEvent;
use Monolog\Test\TestCase;
use Nyholm\Psr7\ServerRequest;

/**
 * @covers \Kuick\App\Events\RequestReceivedEvent
 */
class RequestReceivedEventTest extends TestCase
{
    public function testIfRequestCanBeRetrievedFromTheEvent(): void
    {
        $request = new ServerRequest('GET', '/test');
        $event = new RequestReceivedEvent($request);
        $this->assertEquals($request, $event->getRequest());
    }
}
