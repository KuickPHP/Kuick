<?php

use Kuick\Framework\Config\ListenerConfig;
use Tests\Kuick\Unit\Mocks\MockListener;

return [
    // logging all the events
    new ListenerConfig('*', MockListener::class),
];
