<?php

namespace Kuick\Tests\App\Events;

use Kuick\App\Events\ResponseCreatedEvent;
use Kuick\Http\Message\Response;
use Monolog\Test\TestCase;
use Nyholm\Psr7\ServerRequest;

/**
 * @covers \Kuick\App\Events\ResponseCreatedEvent
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