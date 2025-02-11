<?php

use Kuick\Framework\Config\RouteConfig;
use Tests\Unit\Kuick\Framework\Mocks\MockController;

return [
    new RouteConfig('/hello/(?<userId>[0-9]{1,12})', MockController::class),
    new RouteConfig('/', MockController::class, ['POST']),
    new RouteConfig('/inline', MockController::class, ['GET']),
];
