<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

use Kuick\Framework\Config\RouteConfig;
use Kuick\Http\Message\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

// route configuration
return [
    // Homepage inline route
    new RouteConfig(
        '/',
        function (): JsonResponse {
            return new JsonResponse(['message' => 'Kuick says: hello world!']);
        },
    ),
    // Sample inline route
    new RouteConfig(
        '/ping',
        function (ServerRequestInterface $request): JsonResponse {
            return new JsonResponse([
                'message' => 'pong',
                'request-uri' => $request->getUri()->getPath(),
            ]);
        }
    ),
];
