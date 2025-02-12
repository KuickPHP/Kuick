<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

use Kuick\Framework\Api\UI\DocHtmlController;
use Kuick\Framework\Api\UI\DocJsonController;
use Kuick\Framework\Config\RouteConfig;
use Kuick\Framework\Api\UI\OpsController;
use Kuick\Framework\Api\UI\OptionsController;
use Kuick\Http\Message\RequestInterface;

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
        '.+',
        OptionsController::class,
        [RequestInterface::METHOD_OPTIONS]
    ),
];
