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
use Kuick\Framework\KernelInterface;

/**
 * Listener list factory
 */
class ListenerListFactory
{
    public function build(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            KernelInterface::DI_LISTENERS_KEY => function (
                ConfigIndexer $configIndexer
            ): array {
                $listeners = [];
                foreach ($configIndexer->getConfigFilePaths(ConfigIndexer::LISTENERS_FILE_SUFFIX) as $listenersFile) {
                    $listeners = array_merge($listeners, require $listenersFile);
                }
                return $listeners;
            }
        ]);
    }
}
