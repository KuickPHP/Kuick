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
use Kuick\Framework\Config\ListenerValidator;
use Kuick\Framework\Kernel;
use Psr\Log\LoggerInterface;

class ListenersBuilder
{
    public const CONFIG_SUFFIX = 'listeners';

    public function __construct(private ContainerBuilder $builder)
    {
    }

    public function __invoke(): void
    {
        $this->builder->addDefinitions([Kernel::DI_LISTENERS_KEY =>
        function (
            ConfigIndexer $configIndexer,
            LoggerInterface $logger,
        ): array {
            $validatedListeners = [];
            foreach ($configIndexer->getConfigFiles(ListenersBuilder::CONFIG_SUFFIX, new ListenerValidator()) as $listenersFile) {
                foreach (require $listenersFile as $listener) {
                    $logger->debug('Adding listener: ' . $listener->listenerClassName . ', pattern: ' . $listener->pattern);
                    $validatedListeners[] = $listener;
                }
            }
            return $validatedListeners;
        }]);
    }
}
