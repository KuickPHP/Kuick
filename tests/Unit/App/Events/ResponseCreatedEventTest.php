<?php

namespace Tests\Kuick\Unit\Framework\Events;

use Kuick\Framework\Events\ResponseCreatedEvent;
use Kuick\Http\Message\Response;
use Monolog\Test\TestCase;
use Nyholm\Psr7\ServerRequest;

/**
 * @covers \Kuick\Framework\Events\ResponseCreatedEvent
 */
class ResponseCreatedEventTest extends TestCase
{
    public function testIfResponseCanBeRetrievedFromTheEvent(): void
    {
        $response = new Response();
        $event = new ResponseCreatedEvent($response);
        $this->assertEquals($response, $event->getResponse());
    }
}
