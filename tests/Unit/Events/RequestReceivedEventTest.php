<?php

namespace Tests\Unit\Kuick\Framework\Events;

use Kuick\Framework\Events\RequestReceivedEvent;
use Monolog\Test\TestCase;
use Nyholm\Psr7\ServerRequest;

/**
 * @covers Kuick\Framework\Events\RequestReceivedEvent
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
