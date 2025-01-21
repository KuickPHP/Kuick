<?php

namespace Tests\Kuick\Unit\Mocks;

use Kuick\Http\Message\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MockRequestHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(200, [], 'OK');
    }
}
