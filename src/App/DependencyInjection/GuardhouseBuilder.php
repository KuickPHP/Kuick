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
use Kuick\App\Config\ConfigException;
use Kuick\App\Config\GuardConfig;
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
    public const CONFIG_SUFFIX = 'guards';

    public function __construct(private ContainerBuilder $builder)
    {
    }

    public function __invoke(): void
    {
        $this->builder->addDefinitions([Guardhouse::class => function (ContainerInterface $container, LoggerInterface $logger, SystemCacheInterface $cache): Guardhouse {
            $guardhouse = new Guardhouse($logger);
            foreach ((new ConfigIndexer($cache, $logger))->getConfigFiles($container->get(Kernel::DI_PROJECT_DIR_KEY), GuardhouseBuilder::CONFIG_SUFFIX) as $guardsFile) {
                $guards = include $guardsFile;
                foreach ($guards as $guard) {
                    if (!($guard instanceof GuardConfig)) {
                        throw new ConfigException('Guard config must be an instance of ' . GuardConfig::class);
                    }
                    $logger->info('Adding guard: ' . $guard->path);
                    // @TODO: add support for inline callables
                    $guardhouse->addGuard($guard->path, $container->get($guard->guardClassName), $guard->methods);
                }
            }
            return $guardhouse;
        }]);
    }
}
