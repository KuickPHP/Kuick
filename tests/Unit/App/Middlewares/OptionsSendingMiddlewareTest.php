<?php

namespace Kuick\Tests\App\Middlewares;

use Kuick\App\Middlewares\OptionsSendingMiddleware;
use Kuick\Tests\Mocks\MockRequestHandler;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kuick\App\Middlewares\OptionsSendingMiddleware
 */
class OptionsSendingMiddlewareTest extends TestCase
{
    public function testIfOptionsMethodProduces204(): void
    {
        $middleware = new OptionsSendingMiddleware();
        $response = $middleware->process(new ServerRequest('OPTIONS', '/test'), new MockRequestHandler());
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testIfOtherMethodsArePassed(): void
    {
        $middleware = new OptionsSendingMiddleware();
        $response = $middleware->process(new ServerRequest('GET', '/test'), new MockRequestHandler());
        $this->assertEquals(200, $response->getStatusCode());
    }
}
