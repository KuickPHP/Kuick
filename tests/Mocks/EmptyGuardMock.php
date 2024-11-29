<?php

namespace Tests\Kuick\Mocks;

class EmptyGuardMock
{
    public function __invoke(string $message = ''): void
    {
    }
}
