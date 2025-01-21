<?php

use Kuick\App\Config\GuardConfig;
use Kuick\Tests\Mocks\MockGuard;

return [
    new GuardConfig('/api', MockGuard::class),
];
