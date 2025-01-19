<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\Tests\Mocks\ControllerMock;
use Kuick\Tests\Mocks\RequestDependentControllerMock;
use Kuick\Tests\Mocks\RequestDependentGuardMock;

return [
    [
        'path' => '/hello/(?<userId>[0-9]{1,12})',
        'controller' => ControllerMock::class,
    ],
    [
        'path' => '/',
        'method' => 'POST',
        'controller' => RequestDependentControllerMock::class,
        'guards' => [RequestDependentGuardMock::class],
    ],
];
