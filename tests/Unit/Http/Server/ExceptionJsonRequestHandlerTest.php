<?php

namespace Tests\Unit\Http\Server;

use Kuick\Http\NotFoundException;
use PHPUnit\Framework\TestCase;
use Kuick\Http\Server\ExceptionHtmlRequestHandler;
use Kuick\Http\Server\ExceptionJsonRequestHandler;
use Nyholm\Psr7\ServerRequest;
use Psr\Log\NullLogger;

/**
 * @covers \Kuick\Http\Server\ExceptionJsonRequestHandler
 */
class ExceptionJsonRequestHandlerTest extends TestCase
{
    public function testHandlingNotSpecifiedException(): void
    {
        $handler = new ExceptionJsonRequestHandler(new NullLogger());
        $response = $handler->handle(new ServerRequest('GET', '/something'));
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('{"error":"Internal Server Error"}', $response->getBody()->getContents());
    }

    public function testHandlingSpecificException(): void
    {
        $handler = new ExceptionJsonRequestHandler(new NullLogger());
        $this->assertEquals($handler, $handler->setException(new NotFoundException('Ooops, not found ;)')));
        $response = $handler->handle(new ServerRequest('GET', '/something'));
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('{"error":"Ooops, not found ;)"}', $response->getBody()->getContents());
    }
}
