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
use Kuick\Framework\SystemCache;

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
            SystemCache $systemCache
        ): array {
            $cachedListeners = $systemCache->get('listeners');
            if ($cachedListeners) {
                return $cachedListeners;
            }
            $listeners = [];
            foreach ($configIndexer->getConfigFiles(ListenersBuilder::CONFIG_SUFFIX, new ListenerValidator()) as $listenersFile) {
                $listeners = array_merge($listeners, require $listenersFile);
            }
            $systemCache->set('listeners', $listeners);
            return $listeners;
        }]);
    }
}
