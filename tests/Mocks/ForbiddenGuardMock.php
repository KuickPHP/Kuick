<?php

namespace Tests\Kuick\Mocks;

use Kuick\Http\ForbiddenException;

class ForbiddenGuardMock
{
    public function __invoke(): void
    {
        throw new ForbiddenException('Forbidden');
    }
}
