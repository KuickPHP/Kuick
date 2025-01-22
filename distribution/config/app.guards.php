<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

use Kuick\Framework\Api\Security\OpsGuard;
use Kuick\Framework\Config\GuardConfig;

return [
    // OPS guard protects /api/ops route with OpsGuard 
    // the token can be defined via environment variable
    // @see config/di/kuick.di.php and config/di/kuick.di@dev.php
    new GuardConfig('/api/ops', OpsGuard::class),
];