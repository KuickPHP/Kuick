<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\App\Config\Guard;
use Kuick\Ops\Security\OpsGuard;

return [
    // OPS guard protects /api/ops route with OpsGuard 
    // the token can be defined via environment variable
    // @see config/di/kuick.di.php
    new Guard('/api/ops', OpsGuard::class),
];
