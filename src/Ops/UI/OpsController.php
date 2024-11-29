<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Ops\UI;

use DI\Container;
use Kuick\Http\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

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
