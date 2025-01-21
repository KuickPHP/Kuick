<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Framework\DependencyInjection;

use Kuick\Framework\SystemCacheInterface;
use Psr\Log\LoggerInterface;

/**
 * Config loader
 */
class ConfigIndexer
{
    private const CACHE_KEY_TEMPLATE = 'kuick-app-%s';
    private const ROUTES_CONFIG_LOCATION_TEMPLATE = '/config/*.%s.php';

    public function __construct(
        private SystemCacheInterface $cache,
        private LoggerInterface $logger
    ) {
    }

    public function getConfigFiles(string $projectDir, string $type): array
    {
        $cacheKey = sprintf(self::CACHE_KEY_TEMPLATE, $type);
        $cachedFiles = $this->cache->get($cacheKey);
        if (null !== $cachedFiles) {
            $this->logger->info('Config index of "' . $type . '" loaded from cache: (' . count($cachedFiles) . ')');
            return $cachedFiles;
        }
        $files = [];
        foreach (glob($projectDir . sprintf(self::ROUTES_CONFIG_LOCATION_TEMPLATE, $type)) as $routeFile) {
            $this->logger->info('Indexing ' . $type . ' file: ' . $routeFile);
            $files[] = $routeFile;
        }
        $this->cache->set($cacheKey, $files);
        return $files;
    }
}
