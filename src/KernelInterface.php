<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework;

use Psr\Container\ContainerInterface;

/**
 * Kernel Interface
 */
interface KernelInterface
{
    public const APP_ENV = 'APP_ENV';
    public const ENV_DEV = 'dev';
    public const ENV_PROD = 'prod';

    public const DI_APP_ENV_KEY = 'app.env';
    public const DI_APP_NAME_KEY = 'app.name';
    public const DI_PROJECT_DIR_KEY = 'app.projectDir';

    public function getContainer(): ContainerInterface;
}
