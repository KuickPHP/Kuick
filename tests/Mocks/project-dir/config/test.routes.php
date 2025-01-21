<?php

use Kuick\App\Config\RouteConfig;
use Kuick\Tests\Mocks\MockController;
use Kuick\Tests\Mocks\MockGuard;

return [
    new RouteConfig('/hello/(?<userId>[0-9]{1,12})', MockController::class),
    new RouteConfig('/', MockController::class, ['POST'], [MockGuard::class]),
];
