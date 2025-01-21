<?php

use Kuick\Framework\Config\RouteConfig;
use Tests\Kuick\Unit\Mocks\MockController;

return [
    new RouteConfig('/hello/(?<userId>[0-9]{1,12})', MockController::class),
    new RouteConfig('/', MockController::class, ['POST']),
];
