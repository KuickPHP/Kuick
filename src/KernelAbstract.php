<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework;

use Kuick\Framework\Config\ConfigIndexer;
use Kuick\Framework\DependencyInjection\ContainerCreator;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Log\LoggerInterface;

/**
 * Abstract Kernel
 */
class KernelAbstract implements KernelInterface
{
    private ContainerInterface $container;

    public function __construct(string $projectDir)
    {
        // building DI container
        $this->container = (new ContainerCreator())->create($projectDir);
        $logger = $this->getContainer()->get(LoggerInterface::class);
        $logger->info('Kernel created for: ' . $projectDir);
        $configIndexer = $this->getContainer()->get(ConfigIndexer::class);
        $listenerProvider = $this->getContainer()->get(ListenerProviderInterface::class);
        // registering listeners "on the fly", as they can depend on EventDispatcher
        foreach ($configIndexer->getConfigFilePaths(ConfigIndexer::LISTENERS_FILE_SUFFIX) as $listenerConfigFile) {
            foreach (require $listenerConfigFile as $listenerConfig) {
                $logger->debug('Registering listener: ' . $listenerConfig->listenerClassName);
                $listenerProvider->registerListener(
                    $listenerConfig->pattern,
                    $this->getContainer()->get($listenerConfig->listenerClassName),
                    $listenerConfig->priority
                );
            }
        }
        $logger->info('Listener provider initialized');
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
