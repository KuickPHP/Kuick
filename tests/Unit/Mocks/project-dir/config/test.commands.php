<?php

use Kuick\Framework\Config\CommandConfig;
use Tests\Unit\Kuick\Framework\Mocks\MockCommand;

return [
    new CommandConfig('test', MockCommand::class),
];
