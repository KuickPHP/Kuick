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
use Kuick\App\Config\ConfigException;
use Kuick\App\Config\MiddlewareConfig;
use Kuick\App\SystemCacheInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Log\LoggerInterface;

/**
 * Middleware config loader
 */
class MiddlewareConfigLoader
{
    private const CACHE_KEY = 'kuick-app-middlewares';
    private const MIDDLEWARES_CONFIG_LOCATION = '/config/*.middlewares.php';

    public function __construct(
        private SystemCacheInterface $cache,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(string $projectDir): array
    {
        $cachedMiddlewares = $this->cache->get(self::CACHE_KEY);
        if (null !== $cachedMiddlewares) {
            $this->logger->info('Middlewares (' . count($cachedMiddlewares) . ') loaded from cache');
            return $cachedMiddlewares;
        }
        $middlewares = [];
        $middlewareIterator = new GlobIterator($projectDir . self::MIDDLEWARES_CONFIG_LOCATION, FilesystemIterator::KEY_AS_FILENAME);
        foreach ($middlewareIterator as $middlewareFile) {
            $includedMiddlewares = include $middlewareFile;
            $this->logger->info('Middleware file added: ' . $middlewareFile . ', containing: ' . count($includedMiddlewares) . ' middlewares');
            $middlewares = array_merge($middlewares, $includedMiddlewares);
        }
        // check if middlewares are valid
        foreach ($middlewares as $middlewareClassName) {
            if (!is_string($middlewareClassName)) {
                throw new ConfigException('Middleware must be a string');
            }
            if (!class_exists($middlewareClassName)) {
                throw new ConfigException('Middleware class does not exist: ' . $middlewareClassName);
            }
            if (!in_array(MiddlewareInterface::class, class_implements($middlewareClassName))) {
                throw new ConfigException('Middleware must implement: ' . MiddlewareInterface::class);
            }
        }
        $this->cache->set(self::CACHE_KEY, $middlewares);
        $this->logger->notice('Middlewares parsed (' . count($middlewares) . '), cache written');
        return $middlewares;
    }
}
