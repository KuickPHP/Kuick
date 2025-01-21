<?php

namespace Tests\Kuick\Unit\Http;

use Kuick\Http\Server\ExceptionJsonRequestHandler;
use Kuick\Http\Server\RequestHandler;
use Tests\Kuick\Unit\Mocks\MockHttpMiddleware;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * @covers \Kuick\Http\Server\RequestHandler
 */
class RequestHandlerTest extends TestCase
{
    public function testIfHandlerProperlyPassesHandlingToTheExceptionHandler(): void
    {
        $request = new ServerRequest('GET', '/something');
        $exceptionHandler = new ExceptionJsonRequestHandler(new NullLogger());
        $handler = new RequestHandler($exceptionHandler);
        $response = $handler->handle($request);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('{"error":"Not found"}', $response->getBody()->getContents());
    }

    public function testIfHandlerProperlyPassesHandlingToTheMiddleware(): void
    {
        $request = new ServerRequest('GET', '/something', [], 'Hello world');
        $exceptionHandler = new ExceptionJsonRequestHandler(new NullLogger());
        $handler = new RequestHandler($exceptionHandler);
        $handler->addMiddleware(new MockHttpMiddleware());
        $response = $handler->handle($request);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello world', $response->getBody()->getContents());
    }
}
