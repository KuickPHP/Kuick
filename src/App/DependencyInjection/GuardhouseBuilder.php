<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\DependencyInjection;

use DI\ContainerBuilder;
use Kuick\App\Kernel;
use Kuick\App\SystemCacheInterface;
use Kuick\Routing\Router;
use Kuick\Security\Guardhouse;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 *
 */
class GuardhouseBuilder
{
    public function __construct(private ContainerBuilder $builder)
    {
    }

    public function __invoke(): void
    {
        $this->builder->addDefinitions([Guardhouse::class => function (ContainerInterface $container, LoggerInterface $logger, SystemCacheInterface $cache): Guardhouse {
            $guards = (new GuardsConfigLoader($cache, $logger))(
                $container->get(Kernel::DI_PROJECT_DIR_KEY),
                $container->get(Kernel::DI_APP_ENV_KEY)
            );
            $guardhouse = new Guardhouse($logger);
            foreach ($guards as $guard) {
                $guardhouse->addGuard($guard->path, $container->get($guard->guardClassName), $guard->methods);
            }
            return $guardhouse;
        }]);
    }
}
