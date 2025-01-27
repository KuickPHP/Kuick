<?php

namespace Tests\Unit\Kuick\Framework\Mocks;

use Kuick\Http\Message\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MockMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new JsonResponse([
            'message' => 'Hello, World!',
            'request-uri' => $request->getUri()->getPath(),
            'next-handler' => get_class($handler),
        ]);
    }
}
