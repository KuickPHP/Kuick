<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\Ops\Security\OpsGuard;
use Kuick\Ops\UI\DocHtmlController;
use Kuick\Ops\UI\DocJsonController;
use Kuick\Ops\UI\OpsController;

return [
    //ops route gives some insight of server environment
    //this route is protected by the Guard (see ./di/kuick.di.php file, and the OpsGuard)
    [
        'path' => '/api/ops',
        'controller' => OpsController::class,
        'guards' => [OpsGuard::class]
    ],
    //OpenAPI / Swagger HTML documentation
    [
        'path' => '/api/doc.json',
        'controller' => DocJsonController::class,
    ],
    [
        'path' => '/api/doc',
        'controller' => DocHtmlController::class,
    ],
];
