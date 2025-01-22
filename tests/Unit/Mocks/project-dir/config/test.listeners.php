<?php

use Kuick\Framework\Config\ListenerConfig;
use Tests\Unit\Kuick\Framework\Mocks\MockListener;

return [
    // logging all the events
    new ListenerConfig('*', MockListener::class),
];
