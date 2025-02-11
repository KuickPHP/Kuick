<?php

use Kuick\Framework\Config\RouteConfig;
use Tests\Unit\Kuick\Framework\Mocks\MockController;

return [
    new RouteConfig('/another-one', MockController::class, ['POST']),
];
