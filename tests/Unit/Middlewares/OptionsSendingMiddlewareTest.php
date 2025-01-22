<?php

namespace Tests\Unit\Kuick\Framework\Middlewares;

use Kuick\Framework\Middlewares\OptionsSendingMiddleware;
use Tests\Unit\Kuick\Framework\Mocks\MockRequestHandler;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

/**
 * @covers Kuick\Framework\Middlewares\OptionsSendingMiddleware
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
