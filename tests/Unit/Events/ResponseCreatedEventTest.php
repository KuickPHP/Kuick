<?php

namespace Tests\Unit\Kuick\Framework\Events;

use Kuick\Framework\Events\ResponseCreatedEvent;
use Kuick\Http\Message\Response;
use Monolog\Test\TestCase;

/**
 * @covers Kuick\Framework\Events\ResponseCreatedEvent
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
