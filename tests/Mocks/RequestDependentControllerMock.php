<?php

namespace Tests\Kuick\Mocks;

use Kuick\Http\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

class RequestDependentControllerMock
{
    public function __invoke(ServerRequestInterface $request): JsonResponse
    {
        return new JsonResponse(['queryParams' => $request->getQueryParams()]);
    }
}
