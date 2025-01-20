<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\App\Config\RouteConfig;
use Kuick\Example\UI\PingController;
use Kuick\Doc\UI\DocHtmlController;
use Kuick\Doc\UI\DocJsonController;
use Kuick\Ops\UI\OpsController;

return [
    //homepage
    new RouteConfig('/', PingController::class),
    // hello route (with named parameter)
    new RouteConfig('/hello/(?<name>[a-zA-Z0-9-]{1,40})', PingController::class),

    // OPS route gives some insight of server environment
    new RouteConfig('/api/ops', OpsController::class),
    //OpenAPI / Swagger HTML documentation
    new RouteConfig('/api/doc.json', DocJsonController::class),
    new RouteConfig('/api/doc', DocHtmlController::class),
];
