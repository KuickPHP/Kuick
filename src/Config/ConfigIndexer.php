<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\Config;

use DI\Attribute\Inject;
use Kuick\Framework\SystemCacheInterface;
use Psr\Log\LoggerInterface;

/**
 * Config indexer
 */
class ConfigIndexer
{
    public const COMMANDS_FILE_SUFFIX = 'commands';
    public const LISTENERS_FILE_SUFFIX = 'listeners';
    public const GUARDS_FILE_SUFFIX = 'guards';
    public const ROUTES_FILE_SUFFIX = 'routes';

    public const VALIDATOR_MAP = [
        self::COMMANDS_FILE_SUFFIX => CommandConfigValidator::class,
        self::LISTENERS_FILE_SUFFIX => ListenerConfigValidator::class,
        self::GUARDS_FILE_SUFFIX => GuardConfigValidator::class,
        self::ROUTES_FILE_SUFFIX => RouteConfigValidator::class,
    ];

    public const CONFIG_MAP = [
        self::COMMANDS_FILE_SUFFIX => CommandConfig::class,
        self::LISTENERS_FILE_SUFFIX => ListenerConfig::class,
        self::GUARDS_FILE_SUFFIX => GuardConfig::class,
        self::ROUTES_FILE_SUFFIX => RouteConfig::class,
    ];

    private const CACHE_KEY_TEMPLATE = 'app-config-%s';
    private const CONFIG_LOCATION_TEMPLATES = [
        '/vendor/kuick/*/config/*.%s.php',
        '/config/*.%s.php',
    ];
    private const ENV_SPECIFIC_CONFIG_LOCATION_TEMPLATES = '/config/*.%s@%s.php';

    public function __construct(
        #[Inject('app.projectDir')] private string $projectDir,
        #[Inject('app.env')] private string $appEnv,
        private SystemCacheInterface $cache,
        private LoggerInterface $logger
    ) {
    }

    public function getConfigFilePaths(string $fileSuffix): array
    {
        // loading from cache
        $cachedFileNames = $this->cache->get($cacheKey = sprintf(self::CACHE_KEY_TEMPLATE, $fileSuffix));
        if ($cachedFileNames) {
            $this->logger->debug('Loading ' . $fileSuffix . ' from cache');
            return $cachedFileNames;
        }
        // unknown config file key
        if (!in_array($fileSuffix, array_keys(self::CONFIG_MAP))) {
            throw new ConfigException('Unknown config file key: ' . $fileSuffix);
        }
        $fileNames = [];
        // iterating over all possible locations
        foreach (self::CONFIG_LOCATION_TEMPLATES as $configPathTemplate) {
            // iterating all files matching the template
            foreach (glob($this->projectDir . sprintf($configPathTemplate, $fileSuffix)) as $fileName) {
                $this->logger->debug('Indexing ' . $fileSuffix . ': ' . $fileName);
                $this->validateFileContents($fileName, $fileSuffix);
                $fileNames[] = $fileName;
            }
        }
        // iterating over all env specific locations
        foreach (glob($this->projectDir . sprintf(self::ENV_SPECIFIC_CONFIG_LOCATION_TEMPLATES, $fileSuffix, $this->appEnv)) as $fileName) {
            $this->logger->debug('Indexing ' . $fileSuffix . ': ' . $fileName);
            $this->validateFileContents($fileName, $fileSuffix);
            $fileNames[] = $fileName;
        }
        // writing an infinte cache
        $this->cache->set($cacheKey, $fileNames);
        return $fileNames;
    }

    private function validateFileContents(string $fileName, string $fileSuffix): void
    {
        // iterating over all config files
        $configObjects = require $fileName;
        // validating if the config file returns an array
        if (!is_array($configObjects)) {
            throw new ConfigException('Config file: ' . $fileName . ' must return an array');
        }
        // validating each config
        foreach ($configObjects as $configObject) {
            if (!is_object($configObject)) {
                throw new ConfigException('Config item is not an object: ' . $fileName);
            }
            if (get_class($configObject) !== self::CONFIG_MAP[$fileSuffix]) {
                throw new ConfigException('Config item is not a: ' . self::CONFIG_MAP[$fileSuffix] . ': ' . $fileName);
            }
            // validating the config object
            $validatorClassName = self::VALIDATOR_MAP[$fileSuffix];
            $validator = new $validatorClassName();
            $validator->validate($configObject);
        }
    }
}
