<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\Framework\Api\UI\DocHtmlController;
use Kuick\Framework\Api\UI\DocJsonController;
use Kuick\Framework\Config\RouteConfig;
use Kuick\Framework\Api\UI\OpsController;

return [
    // OPS route gives some insight of server environment
    new RouteConfig('/api/ops', OpsController::class),
    // OpenAPI documentation
    new RouteConfig('/api/doc.json', DocJsonController::class),
    new RouteConfig('/api/doc', DocHtmlController::class),
];
