<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Security;

use Kuick\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class Guardhouse
{
    private const MATCH_PATTERN = '#^%s$#';

    private array $guards = [];

    public function __construct(private LoggerInterface $logger) {
    }

    public function addGuard(string $path, callable $guard, array $methods = [RequestInterface::METHOD_GET]): self
    {
        $this->guards[] = new ExecutableGuard($path, $guard, $methods);
        return $this;
    }

    /**
     * @TODO: add support for inline callables
     */
    public function matchGuards(ServerRequestInterface $request): array
    {
        $requestMethod = $request->getMethod();
        $matchedGuards = [];
        /**
         * @var ExecutableGuard $guard
         */
        foreach ($this->guards as $guard) {
            //trim right slash
            $requestPath = $request->getUri()->getPath() == '/' ? '/' : rtrim($request->getUri()->getPath(), '/');
            //adding HEAD if GET is present
            $guardMethods = in_array(RequestInterface::METHOD_GET, $guard->methods) ? array_merge([RequestInterface::METHOD_HEAD, $guard->methods], $guard->methods) : $guard->methods;
            $this->logger->debug('Trying guard: ' . $guard->path);
            //matching path
            $results = [];
            $matchResult = preg_match(sprintf(self::MATCH_PATTERN, $guard->path), $requestPath, $results);
            if (!$matchResult) {
                continue;
            }
            //matching method
            if (in_array($requestMethod, $guardMethods)) {
                $this->logger->debug('Matched guard: ' . $guard->path . ' ' . $guard->path);
                $matchedGuards[] = $guard->addParams($this->parseGuardParams($results));
            }
        }
        return $matchedGuards;
    }

    private function parseGuardParams(array $results): array
    {
        $params = [];
        foreach ($results as $key => $value) {
            //not a named param
            if (is_int($key)) {
                continue;
            }
            $params[$key] = $value;
        }
        return $params;
    }
}