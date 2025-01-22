<?php

use Kuick\Framework\Config\GuardConfig;
use Tests\Kuick\Unit\Mocks\MockGuard;

return [
    new GuardConfig('/api', MockGuard::class),
];
