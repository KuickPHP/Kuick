<?php

namespace Tests\Unit\Kuick\Framework\Mocks;

use Kuick\Http\Message\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

class MockController
{
    public function __invoke(ServerRequestInterface $request): JsonResponse
    {
        return new JsonResponse([
            'request-uri' => $request->getUri(),
            'request-body' => $request->getBody(),
        ]);
    }
}
