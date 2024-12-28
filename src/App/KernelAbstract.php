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
use Psr\Log\LoggerInterface;

/**
 * Abstract Application Kernel
 */
abstract class KernelAbstract
{
    public const APP_ENV = 'KUICK_APP_ENV';
    public const ENV_DEV = 'dev';
    public const ENV_PROD = 'prod';

    protected ContainerInterface $container;
    protected LoggerInterface $logger;

    public function __construct(string $projectDir)
    {
        //building DI container
        $this->container = (new AppDIContainerBuilder())($projectDir);
        $this->logger = $this->container->get(LoggerInterface::class);
        $this->logger->debug('Kernel booted');
        //localization setup
        ($this->container->get(AppSetLocalization::class))();
        $this->logger->debug('Localization setup completed');
    }
}
