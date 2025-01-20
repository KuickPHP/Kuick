<?php

use Kuick\App\Config\ListenerConfig;
use Kuick\Tests\Mocks\MockListener;

return [
    // logging all the events
    new ListenerConfig('*', MockListener::class),
];
