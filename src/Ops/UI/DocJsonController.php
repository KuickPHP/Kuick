<?php

namespace Kuick\Ops\UI;

use DI\Attribute\Inject;
use Kuick\Http\JsonResponse;
use OpenApi\Generator;

class DocJsonController
{
    private const SOURCE_PATHS = [
        '/src',
        '/config',
    ];

    public function __construct(#[Inject('kuick.app.project.dir')] private string $projectDir)
    {
    }

    public function __invoke(): JsonResponse
    {
        $openapi = Generator::scan([$this->projectDir . '/src', $this->projectDir . '/config']);
        return new JsonResponse(json_decode($openapi->toJson(), true));
    }
}
