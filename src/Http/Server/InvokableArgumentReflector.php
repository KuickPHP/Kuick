<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http\Server;

use Kuick\App\AppException;
use Kuick\App\SystemCacheInterface;
use Psr\Log\LoggerInterface;
use ReflectionMethod;

/**
 *
 */
class InvokableArgumentReflector
{
    private const METHOD_NAME = '__invoke';
    private const CACHE_PREFIX = 'kuick-http-server-utils-invokable-arguments-';

    public function __construct(
        private SystemCacheInterface $cache,
        private LoggerInterface $logger,
    ) {
    }

    /**
     *
     */
    public function getForClass(string $className): array
    {
        $cachedArguments = $this->cache->get(self::CACHE_PREFIX . $className);
        if (null !== $cachedArguments) {
            $this->logger->info('Arguments for invokable ' . $className . ' loaded from cache');
            return $cachedArguments;
        }
        if (!method_exists($className, self::METHOD_NAME)) {
            throw new AppException('Class not found: ' . $className);
        }
        $reflectionMethod = new ReflectionMethod($className, self::METHOD_NAME);
        $availableParams = [];
        foreach ($reflectionMethod->getParameters() as $methodParam) {
            $availableParams[$methodParam->getName()] = [
                //@phpstan-ignore-next-line
                'type' => $methodParam->getType()->getName(),
                'isOptional' => $methodParam->isOptional(),
                'default' => $methodParam->isDefaultValueAvailable() ? $methodParam->getDefaultValue() : null,
            ];
        }
        $this->cache->set(self::CACHE_PREFIX . $className, $availableParams);
        $this->logger->notice('Reflected arguments for invokable ' . $className);
        return $availableParams;
    }
}
