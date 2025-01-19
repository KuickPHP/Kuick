<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Security;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class Guardhouse
{
    // @TODO: unify with the Http package
    public const METHOD_GET = 'GET';
    public const METHOD_HEAD = 'HEAD';
    public const METHOD_OPTIONS = 'OPTIONS';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_DELETE = 'DELETE';

    private const MATCH_PATTERN = '#^%s$#';

    private array $guards = [];

    public function __construct(private LoggerInterface $logger) {
    }

    public function addGuard(string $path, callable $guard, array $methods = [self::METHOD_GET]): self
    {
        $this->guards[] = new ExecutableGuard($path, $guard, $methods);
        return $this;
    }

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
            $guardMethods = in_array(self::METHOD_GET, $guard->methods) ? array_merge([self::METHOD_HEAD, $guard->methods], $guard->methods) : $guard->methods;
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