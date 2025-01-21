<?php

namespace Tests\Kuick\Unit\Framework\Listeners;

use Kuick\Framework\Events\ResponseCreatedEvent;
use Kuick\Framework\Listeners\ResponseEmittingListener;
use Kuick\Http\Message\Response;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kuick\Framework\Listeners\ResponseEmittingListener
 */
class ResponseEmittingTest extends TestCase
{
    /**
     * Needs to be run in separate process, cause emmiter sends headers
     * @runInSeparateProcess
     */
    public function testIfResponseIsEmitted(): void
    {
        $responseCreatedEvent = new ResponseCreatedEvent(new Response(200, [], 'Hello world!'));
        ob_start();
        (new ResponseEmittingListener())($responseCreatedEvent);
        $this->assertEquals('Hello world!', ob_get_clean());
    }
}
