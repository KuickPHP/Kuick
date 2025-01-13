<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\DIFactories\Utils;

use FilesystemIterator;
use GlobIterator;
use Kuick\App\KernelInterface;
use Kuick\App\SystemCacheInterface;
use Psr\Log\LoggerInterface;

/**
 * Listener config loader
 */
class ListenerConfigLoader
{
    private const CACHE_KEY = 'kuick-app-event-listeners';
    private const LISTENERS_LOCATIONS = [
        '/vendor/kuick/*/config/listeners/*.listeners.php',
        '/config/listeners/*.listeners.php',
    ];

    public function __construct(
        private SystemCacheInterface $cache,
        private LoggerInterface $logger
    )
    {
    }

    public function __invoke(string $projectDir): array
    {
        $cachedListeners = $this->cache->get(self::CACHE_KEY);
        if (null !== $cachedListeners) {
            $this->logger->info('Listeners (' . count($cachedListeners) . ') loaded from cache');
            return $cachedListeners;
        }
        $listeners = [];
        foreach (self::LISTENERS_LOCATIONS as $listenerLocation) {
            $listenerIterator = new GlobIterator($projectDir . $listenerLocation, FilesystemIterator::KEY_AS_FILENAME);
            foreach ($listenerIterator as $listenerFile) {
                $includedListeners = include $listenerFile;
                $this->logger->info('Listeners file added: ' . $listenerFile . ', containing: ' . count($includedListeners) . ' listeners');
                $listeners = array_merge($listeners, include $listenerFile);
            }
        }
        $this->cache->set(self::CACHE_KEY, $listeners);
        $this->logger->notice('Listeners parsed (' . count($listeners) . '), cache written');
        return $listeners;
    }
}
