<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\DependencyInjection;

use DI\ContainerBuilder;
use Kuick\App\Kernel;
use Kuick\App\SystemCacheInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class EventDispatcherBuilder
{
    public function __construct(private ContainerBuilder $builder)
    {
    }

    public function __invoke(): void
    {
        $this->builder->addDefinitions([Kernel::DI_LISTENERS_KEY => function (ContainerInterface $container, LoggerInterface $logger, SystemCacheInterface $cache) {
            return (new ListenerConfigLoader($cache, $logger))($container->get(Kernel::DI_PROJECT_DIR_KEY));
        }]);
    }
}
