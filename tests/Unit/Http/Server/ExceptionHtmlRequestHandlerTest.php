<?php

namespace Tests\Unit\Http\Server;

use Kuick\Http\NotFoundException;
use PHPUnit\Framework\TestCase;
use Kuick\Http\Server\ExceptionHtmlRequestHandler;
use Nyholm\Psr7\ServerRequest;
use Psr\Log\NullLogger;

/**
 * @covers \Kuick\Http\Server\ExceptionHtmlRequestHandler
 */
class ExceptionHtmlRequestHandlerTest extends TestCase
{
    public function testHandlingNotSpecifiedException(): void
    {
        $handler = new ExceptionHtmlRequestHandler(new NullLogger());
        $response = $handler->handle(new ServerRequest('GET', '/something'));
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('<h1>Internal Server Error</h1>', $response->getBody()->getContents());
    }

    public function testHandlingSpecificException(): void
    {
        $handler = new ExceptionHtmlRequestHandler(new NullLogger());
        $this->assertEquals($handler, $handler->setException(new NotFoundException('Ooops, not found ;)')));
        $response = $handler->handle(new ServerRequest('GET', '/something'));
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('<h1>Ooops, not found ;)</h1>', $response->getBody()->getContents());
    }
}
