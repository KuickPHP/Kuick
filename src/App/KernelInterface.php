<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Kernel interface
 */
interface KernelInterface
{
    public const APP_ENV = 'KUICK_APP_ENV';
    public const ENV_DEV = 'dev';
    public const ENV_PROD = 'prod';

    public function getContainer(): ContainerInterface;
    public function getEventDispatcher(): EventDispatcherInterface;
}