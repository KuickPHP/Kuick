<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\DependencyInjection;

use FilesystemIterator;
use GlobIterator;
use Kuick\App\AppException;
use Kuick\App\Config\GuardConfig;
use Kuick\App\SystemCacheInterface;
use Psr\Log\LoggerInterface;

/**
 * Guard config loader
 */
class GuardsConfigLoader
{
    private const CACHE_KEY = 'kuick-app-guards';
    private const GUARDS_CONFIG_LOCATION = '/config/*.guards.php';

    public function __construct(
        private SystemCacheInterface $cache,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(string $projectDir): array
    {
        $cachedGuards = $this->cache->get(self::CACHE_KEY);
        if (null !== $cachedGuards) {
            $this->logger->info('Guards (' . count($cachedGuards) . ') loaded from cache');
            return $cachedGuards;
        }
        $guards = [];
        $guardIterator = new GlobIterator($projectDir . self::GUARDS_CONFIG_LOCATION, FilesystemIterator::KEY_AS_FILENAME);
        foreach ($guardIterator as $guardFile) {
            $includedGuards = include $guardFile;
            $this->logger->info('Guard file added: ' . $guardFile . ', containing: ' . count($includedGuards) . ' guards');
            $guards = array_merge($guards, $includedGuards);
        }
        foreach ($guards as $guard) {
            if (!($guard instanceof GuardConfig)) {
                throw new AppException('Guard must be an instance of ' . GuardConfig::class);
            }
        }
        $this->cache->set(self::CACHE_KEY, $guards);
        $this->logger->notice('Guards parsed (' . count($guards) . '), cache written');
        return $guards;
    }
}
