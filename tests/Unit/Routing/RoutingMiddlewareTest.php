<?php

namespace Tests\Kuick\Unit\Routing;

use Kuick\Http\Message\Response;
use Kuick\Http\MethodNotAllowedException;
use Kuick\Routing\Router;
use Kuick\Routing\RoutingMiddleware;
use Tests\Kuick\Unit\Mocks\MockRequestHandler;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\NullLogger;

/**
 * @covers \Kuick\Routing\RoutingMiddleware
 */
class RoutingMiddlewareTest extends TestCase
{
    public function testIfEmptyRoutersExceptionIsFreelyPassed(): void
    {
        $emptyRouter = new Router(new NullLogger());
        $routingMiddleware = new RoutingMiddleware($emptyRouter, new NullLogger());
        $response = $routingMiddleware->process(new ServerRequest('GET', '/test'), new MockRequestHandler());
        //fallback response from MockRequestHandler
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getBody()->getContents());
    }

    public function testAddingAndMatchingMultipleRoutes(): void
    {
        $controllerMock = function (ServerRequestInterface $request): ResponseInterface {
            return new Response(200, [], $request->getBody()->getContents());
        };
        $router = (new Router(new NullLogger()))
            ->addRoute('/sample', $controllerMock, ['GET'])
            ->addRoute('/sample', $controllerMock, ['POST'])
            ->addRoute('/test', $controllerMock, ['GET', 'POST']);

        $routingMiddleware = new RoutingMiddleware($router, new NullLogger());
        $response = $routingMiddleware->process(new ServerRequest('GET', '/sample', [], 'Hello world!'), new MockRequestHandler());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello world!', $response->getBody()->getContents());
    }

    public function testHandlingRoutesWithInvalidMethod(): void
    {
        $controllerMock = function (ServerRequestInterface $request): ResponseInterface {
            return new Response(200, [], $request->getBody()->getContents());
        };
        $router = (new Router(new NullLogger()))
            ->addRoute('/test', $controllerMock, ['GET']);

        $routingMiddleware = new RoutingMiddleware($router, new NullLogger());
        $this->expectException(MethodNotAllowedException::class);
        $routingMiddleware->process(new ServerRequest('POST', '/test', [], 'Hello world!'), new MockRequestHandler());
    }
}
