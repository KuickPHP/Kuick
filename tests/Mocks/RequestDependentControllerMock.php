<?php

namespace Kuick\Tests\Mocks;

use Kuick\Http\Message\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

class RequestDependentControllerMock
{
    public function __invoke(ServerRequestInterface $request): JsonResponse
    {
        return new JsonResponse(['queryParams' => $request->getQueryParams()]);
    }
}
