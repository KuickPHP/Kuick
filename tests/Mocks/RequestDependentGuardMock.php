<?php

namespace Kuick\Tests\Mocks;

use Kuick\Http\ForbiddenException;
use Psr\Http\Message\ServerRequestInterface;

class RequestDependentGuardMock
{
    public function __invoke(ServerRequestInterface $request): void
    {
        throw new ForbiddenException($request->getBody()->getContents());
    }
}
