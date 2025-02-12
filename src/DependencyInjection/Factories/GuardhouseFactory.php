<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\DependencyInjection\Factories;

use DI\ContainerBuilder;
use Kuick\Framework\Config\ConfigIndexer;
use Kuick\Security\Guardhouse;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Guardhouse factory
 */
class GuardhouseFactory
{
    public function build(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            Guardhouse::class => function (
                ConfigIndexer $configIndexer,
                ContainerInterface $container,
                LoggerInterface $logger
            ): Guardhouse {
                $guardhouse = new Guardhouse($logger);
                foreach ($configIndexer->getConfigFilePaths(ConfigIndexer::GUARDS_FILE_SUFFIX) as $guardsFile) {
                    foreach (require $guardsFile as $guard) {
                        $logger->debug('Adding guard: ' . $guard->path);
                        $guardhouse->addGuard($guard->path, $container->get($guard->guardClassName), $guard->methods);
                    }
                }
                $logger->debug('Security guardhouse initialized');
                return $guardhouse;
            }
        ]);
    }
}
