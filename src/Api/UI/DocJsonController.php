<?php

namespace Kuick\Framework\Api\UI;

use DI\Attribute\Inject;
use Kuick\Http\Message\JsonResponse;
use OpenApi\Attributes as OAA;
use OpenApi\Generator;

#[OAA\Info(title: 'Kuick Framework API', version: '1.2')]
#[OAA\Get(
    path: '/api/doc.json',
    description: 'Returns OpenApi Documentation JSON',
    tags: ['API'],
    responses: [
        new OAA\Response(
            response: JsonResponse::HTTP_OK,
            description: 'Array with environment variables',
            content: new OAA\JsonContent()
        ),
    ]
)]
final class DocJsonController
{
    private const SOURCE_PATH = '/src';

    public function __construct(#[Inject('app.projectDir')] private string $projectDir)
    {
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __invoke(): JsonResponse
    {
        $openapi = Generator::scan([$this->projectDir . self::SOURCE_PATH]);
        return new JsonResponse(json_decode($openapi->toJson(), true));
    }
}
