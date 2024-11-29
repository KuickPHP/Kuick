<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\Example\UI\PingController;
use Kuick\Ops\Security\OpsGuard;
use Kuick\Ops\UI\OpsController;

return [
    [
        'path' => '/',
        'controller' => PingController::class,
    ],
    [
        'path' => '/hello/(?<name>[a-zA-Z0-9-]+)',
        'controller' => PingController::class,
    ],
    [
        'path' => '/api/ops',
        'controller' => OpsController::class,
        'guards' => [OpsGuard::class]
    ],
];
