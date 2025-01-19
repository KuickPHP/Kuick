<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\DependencyInjection;

use FilesystemIterator;
use GlobIterator;
use Kuick\App\AppException;
use Kuick\App\Config\Guard;
use Kuick\App\SystemCacheInterface;
use Psr\Log\LoggerInterface;

/**
 * Guard config loader
 */
class GuardsConfigLoader
{
    private const CACHE_KEY = 'kuick-app-guards';
    private const Guard_LOCATIONS = [
        //@TODO: remove this (attach files to the distribution)
        '/vendor/kuick/*/config/*.guards.php',
        '/config/*.guards.php',
    ];

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
        foreach (self::Guard_LOCATIONS as $guardLocation) {
            $guardIterator = new GlobIterator($projectDir . $guardLocation, FilesystemIterator::KEY_AS_FILENAME);
            foreach ($guardIterator as $guardFile) {
                $includedGuards = include $guardFile;
                $this->logger->info('Guard file added: ' . $guardFile . ', containing: ' . count($includedGuards) . ' guards');
                $guards = array_merge($guards, $includedGuards);
            }
        }
        foreach ($guards as $guard) {
            if (!($guard instanceof Guard)) {
                throw new AppException('Guard must be an instance of ' . Guard::class);
            }
        }
        $this->cache->set(self::CACHE_KEY, $guards);
        $this->logger->notice('Guards parsed (' . count($guards) . '), cache written');
        return $guards;
    }
}
