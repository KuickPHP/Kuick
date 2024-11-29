<?php

namespace Tests\Kuick\Mocks;

use Kuick\Security\GuardInterface;
use Psr\Http\Message\ServerRequestInterface;

class EmptyGuardMock implements GuardInterface
{
    public function __invoke(ServerRequestInterface $request): void
    {
    }
}
