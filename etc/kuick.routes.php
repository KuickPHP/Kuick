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
    //you probably want to remove this sample homepage
    [
        'path' => '/',
        //optional method, defaults to GET
        //'method' => 'GET',
        'controller' => PingController::class,
    ],
    //named parameter see how the PingController handles such request
    [
        'path' => '/hello/(?<name>[a-zA-Z0-9-]+)',
        'controller' => PingController::class,
    ],
    //ops route gives some insight of server environment
    //this route is protected by the Guard (see ./di/kuick.di.php file, and the OpsGuard)
    [
        'path' => '/api/ops',
        'controller' => OpsController::class,
        'guards' => [OpsGuard::class]
    ],
];
