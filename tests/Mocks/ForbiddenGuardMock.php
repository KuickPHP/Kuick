<?php

namespace Tests\Kuick\Mocks;

use Kuick\Http\ForbiddenException;
use Kuick\Security\GuardInterface;
use Psr\Http\Message\ServerRequestInterface;

class ForbiddenGuardMock implements GuardInterface
{
    public function __invoke(ServerRequestInterface $request): void
    {
        throw new ForbiddenException('Forbidden');
    }
}