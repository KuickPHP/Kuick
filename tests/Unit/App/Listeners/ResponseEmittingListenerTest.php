<?php

namespace Kuick\Tests\App\Listeners;

use Kuick\App\Events\ResponseCreatedEvent;
use Kuick\App\Listeners\ResponseEmittingListener;
use Kuick\Http\Message\Response;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kuick\App\Listeners\ResponseEmittingListener
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
