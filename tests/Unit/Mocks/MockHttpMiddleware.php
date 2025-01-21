<?php

namespace Tests\Kuick\Unit\Mocks;

use Kuick\Http\Message\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MockHttpMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ('GET' === $request->getMethod()) {
            return new Response(200, [], $request->getBody()->getContents());
        }
        return $handler->handle($request);
    }
}
