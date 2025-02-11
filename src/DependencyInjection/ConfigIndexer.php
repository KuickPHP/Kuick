<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\DependencyInjection;

use DI\Attribute\Inject;
use Kuick\Framework\Config\ConfigException;
use Kuick\Framework\Config\ConfigValidatorInterface;
use Kuick\Framework\SystemCacheInterface;
use Psr\Log\LoggerInterface;

/**
 * Config loader
 */
class ConfigIndexer
{
    private const CACHE_KEY_TEMPLATE = 'kuick-app-%s';
    private const CONFIG_LOCATION_TEMPLATES = [
        '/vendor/kuick/*/config/*.%s.php',
        '/config/*.%s.php',
    ];

    public function __construct(
        #[Inject('app.projectDir')] private string $projectDir,
        private SystemCacheInterface $cache,
        private LoggerInterface $logger
    ) {
    }

    public function getConfigFiles(string $type, ConfigValidatorInterface $validator): array
    {
        $cacheKey = sprintf(self::CACHE_KEY_TEMPLATE, $type);
        $cachedFiles = $this->cache->get($cacheKey);
        if (null !== $cachedFiles) {
            $this->logger->debug('Config index of "' . $type . '" loaded from cache: (' . count($cachedFiles) . ')');
            return $cachedFiles;
        }
        $files = [];
        // iterating over all possible locations
        foreach (self::CONFIG_LOCATION_TEMPLATES as $configPathTemplate) {
            // iterating all files matching the template
            foreach (glob($this->projectDir . sprintf($configPathTemplate, $type)) as $configFile) {
                $this->logger->debug('Indexing ' . $type . ' config: ' . $configFile);
                $files[] = $configFile;
            }
        }
        // iterating over all config files
        foreach ($files as $file) {
            $configObjects = require $file;
            // validating if the config file returns an array
            if (!is_array($configObjects)) {
                throw new ConfigException('Config file:' . $file . ' must return an array');
            }
            // validating each config
            foreach ($configObjects as $configObject) {
                if (!is_object($configObject)) {
                    throw new ConfigException('One or more config item from: ' . $file . ' is not an object');
                }
                $validator->validate($configObject);
            }
        }
        $this->cache->set($cacheKey, $files);
        return $files;
    }
}
