<?php

namespace Kuick\Tests\Mocks;

use Kuick\Http\Message\JsonResponse;

class ControllerMock
{
    public function __invoke(int $userId): JsonResponse
    {
        return new JsonResponse(['userId' => $userId]);
    }
}
