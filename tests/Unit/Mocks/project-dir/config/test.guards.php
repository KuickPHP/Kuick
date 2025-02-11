<?php

use Kuick\Framework\Config\GuardConfig;
use Kuick\Http\Message\JsonResponse;
use Tests\Unit\Kuick\Framework\Mocks\MockGuard;

return [
    new GuardConfig('/api', MockGuard::class),
    new GuardConfig('/another', MockGuard::class, ['GET']),
];
