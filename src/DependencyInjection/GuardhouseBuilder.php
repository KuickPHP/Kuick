<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\DependencyInjection;

use Closure;
use DI\ContainerBuilder;
use Kuick\Framework\Config\ConfigException;
use Kuick\Framework\Config\GuardConfig;
use Kuick\Framework\Config\GuardValidator;
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
            LoggerInterface $logger
        ): Guardhouse {
            $guardhouse = new Guardhouse($logger);
            foreach ($configIndexer->getConfigFiles(GuardhouseBuilder::CONFIG_SUFFIX, new GuardValidator()) as $guardsFile) {
                foreach (require $guardsFile as $guard) {
                    $logger->debug('Adding guard: ' . $guard->path);
                    // getting from container if guard is a string
                    $callable = $guard->guard instanceof Closure ?
                        $guard->guard :
                        $container->get($guard->guard);
                    $guardhouse->addGuard($guard->path, $callable, $guard->methods);
                }
            }
            $logger->debug('Security guardhouse initialized');
            return $guardhouse;
        }]);
    }
}
