<?php

namespace Tests\Unit\Kuick\Framework\Events;

use Kuick\Framework\Events\ResponseEmittedEvent;
use Kuick\Http\Message\Response;
use Monolog\Test\TestCase;

/**
 * @covers Kuick\Framework\Events\ResponseEmittedEvent
 */
class ResponseEmittedEventTest extends TestCase
{
    public function testIfResponseCanBeRetrievedFromTheEvent(): void
    {
        $response = new Response();
        $event = new ResponseEmittedEvent($response);
        $this->assertEquals($response, $event->getResponse());
    }
}
