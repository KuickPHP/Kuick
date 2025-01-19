<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\App\Config\Route;
use Kuick\Example\UI\PingController;
use Kuick\Ops\UI\DocHtmlController;
use Kuick\Ops\UI\DocJsonController;
use Kuick\Ops\UI\OpsController;

return [
    //homepage
    new Route('/', PingController::class),
    // hello route (with named parameter)
    new Route('/hello/(?<name>[a-zA-Z0-9-]{1,40})', PingController::class),

    // OPS route gives some insight of server environment
    new Route('/api/ops', OpsController::class),
    //OpenAPI / Swagger HTML documentation
    new Route('/api/doc.json', DocJsonController::class),
    new Route('/api/doc', DocHtmlController::class),
];
