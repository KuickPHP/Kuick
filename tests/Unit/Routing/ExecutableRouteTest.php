<?php

namespace Kuick\Tests\Unit\Routing;

use Kuick\Http\Message\Response;
use PHPUnit\Framework\TestCase;
use Kuick\Routing\ExecutableRoute;
use Nyholm\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @covers \Kuick\Routing\ExecutableRoute
 */
class ExecutableRouteTest extends TestCase
{
    public function testIfRouteIsExecutable(): void
    {
        $controllerMock = function (ServerRequestInterface $request): ResponseInterface {
            $response = new Response(200, [], $request->getBody()->getContents());
            return $response->withAddedHeader('X-Param', $request->getQueryParams()['param']);
        };
        $executableRoute = new ExecutableRoute('/test', $controllerMock, ['GET']);
        $executableRoute->setParams(['param' => 'Hello world']);
        $response = $executableRoute->execute(new ServerRequest('GET', '/test', [], 'Request body'));
        $this->assertEquals('Request body', $response->getBody()->getContents());
        $this->assertEquals('Hello world', $response->getHeaderLine('X-Param'));
    }
}
