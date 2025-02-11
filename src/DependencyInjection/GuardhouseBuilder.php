<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\DependencyInjection;

use DI\ContainerBuilder;
use Kuick\Framework\Config\GuardValidator;
use Kuick\Framework\SystemCache;
use Kuick\Security\Guardhouse;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Guardhouse Builder
 */
class GuardhouseBuilder
{
    public const CONFIG_SUFFIX = 'guards';

    public function __construct(private ContainerBuilder $builder)
    {
    }

    public function __invoke(): void
    {
        $this->builder->addDefinitions([Guardhouse::class =>
        function (
            ConfigIndexer $configIndexer,
            ContainerInterface $container,
            LoggerInterface $logger,
            SystemCache $systemCache,
        ): Guardhouse {
            $cachedGuardhouse = $systemCache->get('guardhouse');
            if ($cachedGuardhouse) {
                $logger->debug('Guardhouse loaded from cache');
                return $cachedGuardhouse;
            }
            $guardhouse = new Guardhouse($logger);
            foreach ($configIndexer->getConfigFiles(GuardhouseBuilder::CONFIG_SUFFIX, new GuardValidator()) as $guardsFile) {
                foreach (require $guardsFile as $guard) {
                    $logger->debug('Adding guard: ' . $guard->path);
                    $guardhouse->addGuard($guard->path, $container->get($guard->guardClassName), $guard->methods);
                }
            }
            $logger->debug('Security guardhouse initialized');
            $systemCache->set('guardhouse', $guardhouse);
            return $guardhouse;
        }]);
    }
}
