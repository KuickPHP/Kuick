<?php

namespace Kuick\Tests\Http\Server;

use Kuick\Http\ForbiddenException;
use Kuick\Http\Server\ActionHandler;
use Kuick\Http\Server\JsonMiddleware;
use Kuick\Http\Server\Router;
use Kuick\Tests\Mocks\ActionHandlerThrowingException;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\NullLogger;
use Kuick\Tests\Mocks\ContainerMock;
use Kuick\Tests\Mocks\ControllerMock;
use Kuick\Tests\Mocks\EmptyActionHandler;
use Kuick\Tests\Mocks\EmptyGuardMock;
use Kuick\Tests\Mocks\ForbiddenGuardMock;
use Kuick\Tests\Mocks\RequestDependentControllerMock;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\Http\Server\JsonMiddleware
 */
class JsonMiddlewareTest extends TestCase
{
    public function testIfMiddlewareProducesAResponse(): void
    {
        $middleware = new JsonMiddleware(new NullLogger);
        $response = $middleware->process(new ServerRequest('GET', '/whatever'), new EmptyActionHandler);
        
        assertEquals(200, $response->getStatusCode());
        assertEquals('example', $response->getBody()->getContents());
    }

    public function testIfMiddlewareProducesAJsonResponseEvenIfExceptionIsThrown(): void
    {
        $middleware = new JsonMiddleware(new NullLogger);
        $response = $middleware->process(new ServerRequest('GET', '/whatever'), new ActionHandlerThrowingException);
        
        assertEquals(500, $response->getStatusCode());
        assertEquals('{"error":"some exception"}', $response->getBody()->getContents());
    }
}
