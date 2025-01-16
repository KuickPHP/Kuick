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
use Kuick\App\SystemCacheInterface;
use Kuick\Http\Server\Middleware;
use Psr\Log\LoggerInterface;

/**
 * Middleware config loader
 */
class MiddlewareConfigLoader
{
    private const CACHE_KEY = 'kuick-app-middlewares';

    private const MIDDLEWARE_LOCATIONS = [
        '/vendor/kuick/*/config/*.middlewares.php',
        '/config/*.middlewares.php',
    ];

    public function __construct(
        private SystemCacheInterface $cache,
        private LoggerInterface $logger
    )
    {
    }

    public function __invoke(string $projectDir): array
    {
        $cachedMiddlewares = $this->cache->get(self::CACHE_KEY);
        if (null !== $cachedMiddlewares) {
            $this->logger->info('Middlewares (' . count($cachedMiddlewares) . ') loaded from cache');
            return $cachedMiddlewares;
        }
        $middlewares = [];
        foreach (self::MIDDLEWARE_LOCATIONS as $middlewareLocation) {
            $middlewareIterator = new GlobIterator($projectDir . $middlewareLocation, FilesystemIterator::KEY_AS_FILENAME);
            foreach ($middlewareIterator as $middlewareFile) {
                $includedMiddlewares = include $middlewareFile;
                $this->logger->info('Middleware file added: ' . $middlewareFile . ', containing: ' . count($includedMiddlewares) . ' middlewares');
                $middlewares = array_merge($middlewares, $includedMiddlewares);
            }
        }
        foreach ($middlewares as $middleware) {
            if (!($middleware instanceof Middleware)) {
                throw new AppException('Middleware must be an instance of ' . Middleware::class);
            }
        }
        $this->cache->set(self::CACHE_KEY, $middlewares);
        return $middlewares;
    }
}
