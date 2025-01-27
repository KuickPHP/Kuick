<?php

/**
 * Kuick Project (https://github.com/milejko/kuick-project)
 *
 * @link       https://github.com/milejko/kuick-project
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-project?tab=MIT-1-ov-file#readme New BSD License
 */

use Kuick\Framework\Api\UI\DocHtmlController;
use Kuick\Framework\Api\UI\DocJsonController;
use Kuick\Framework\Config\RouteConfig;
use Kuick\Framework\Api\UI\OpsController;
use Kuick\Http\Message\RequestInterface;
use Kuick\Http\Message\Response;

return [
    // OPS route gives some insight of server environment
    new RouteConfig(
        '/api/ops',
        OpsController::class
    ),
    // OpenAPI JSON documentation
    new RouteConfig(
        '/api/doc.json',
        DocJsonController::class
    ),
    // OpenAPI HTML documentation
    new RouteConfig(
        '/api/doc',
        DocHtmlController::class
    ),
    // catching all paths OPTIONS route
    new RouteConfig(
        '[.]+',
        function (): Response {
            return new Response(Response::HTTP_NO_CONTENT);
        },
        [RequestInterface::METHOD_OPTIONS]
    ),
];
