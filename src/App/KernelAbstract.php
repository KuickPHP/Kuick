<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
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

    public function __construct()
    {
        set_error_handler(function (int $errno, string $message): void {
            throw new AppException($message, $errno);
        });
        //building DI container
        $this->container = (new AppDIContainerBuilder())();
        $this->logger = $this->container->get(LoggerInterface::class);
        //localization setup
        ($this->container->get(AppSetLocalization::class))();
        $this->logger->debug('Localization setup completed');
    }
}
