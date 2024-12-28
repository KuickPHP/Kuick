<?php

namespace Kuick\Tests\Mocks;

class EmptyGuardMock
{
    public function __invoke(string $message = ''): void
    {
    }
}
