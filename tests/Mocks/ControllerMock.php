<?php

namespace Tests\Kuick\Mocks;

use Kuick\Http\JsonResponse;

class ControllerMock
{
    public function __invoke(int $userId): JsonResponse
    {
        return new JsonResponse(['userId' => $userId]);
    }
}