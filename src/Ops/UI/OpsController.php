<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Ops\UI;

use DI\Container;
use Kuick\Http\Message\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use OpenApi\Attributes as OAA;

#[OAA\Get(
    path: '/api/ops',
    description: 'Returns environment variables',
    tags: ['API'],
    security: [['Bearer Token' => []]],
    responses: [
        new OAA\Response(
            response: JsonResponse::HTTP_OK,
            description: 'Array with environment variables',
            content: new OAA\JsonContent(properties: [
                new OAA\Property(property: 'request', type: 'object'),
                new OAA\Property(property: 'di-config', type: 'object'),
                new OAA\Property(property: 'php-version'),
                new OAA\Property(property: 'php-config'),
                new OAA\Property(property: 'php-loaded-extensions'),
            ])
        ),
        new OAA\Response(
            response: JsonResponse::HTTP_UNAUTHORIZED,
            description: 'Token is not present',
            content: new OAA\JsonContent(properties: [
                new OAA\Property(property: "error", type: "string"),
            ])
        ),
        new OAA\Response(
            response: JsonResponse::HTTP_FORBIDDEN,
            description: 'Token is invalid',
            content: new OAA\JsonContent(properties: [
                new OAA\Property(property: "error", type: "string"),
            ])
        ),
    ]
)]
class OpsController
{
    public function __construct(private Container $container)
    {
    }

    public function __invoke(ServerRequestInterface $request): JsonResponse
    {
        return new JsonResponse([
            'request' => [
                'method' => $request->getMethod(),
                'uri' => $request->getUri(),
                'headers' => $request->getHeaders(),
                'path' => $request->getUri()->getPath(),
                'queryParams' => $request->getUri()->getQuery(),
                'body' => $request->getBody()->getContents(),
            ],
            'di-config' => $this->getConfigDefinitions(),
            'php-version' => phpversion(),
            'php-config' => ini_get_all(null, false),
            'php-loaded-extensions' => implode(', ', get_loaded_extensions()),
        ]);
    }

    private function getConfigDefinitions(): array
    {
        $configValues = [];
        //iterating DI keys
        foreach ($this->container->getKnownEntryNames() as $entryName) {
            //getting value from container
            $configValues[$entryName] = $this->container->get($entryName);
        }
        return $configValues;
    }
}
