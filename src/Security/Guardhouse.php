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

    public function __construct(private LoggerInterface $logger)
    {
    }

    public function addGuard(
        string $path,
        callable $guard,
        array $methods = [
            RequestInterface::METHOD_GET,
            RequestInterface::METHOD_POST,
            RequestInterface::METHOD_PUT,
            RequestInterface::METHOD_PATCH,
            RequestInterface::METHOD_DELETE,
            RequestInterface::METHOD_OPTIONS,
        ]
    ): self {
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
            $guardMethods = in_array(RequestInterface::METHOD_GET, $guard->methods) ? array_merge([RequestInterface::METHOD_HEAD, $guard->methods], $guard->methods) : $guard->methods;
            $this->logger->debug('Trying guard: ' . $guard->path);
            //matching path
            $pathParams = [];
            $matchResult = preg_match(sprintf(self::MATCH_PATTERN, $guard->path), $requestPath, $pathParams);
            if (!$matchResult) {
                continue;
            }
            //matching method
            if (in_array($requestMethod, $guardMethods)) {
                $this->logger->debug('Matched guard: ' . $guard->path . ' ' . $guard->path);
                $matchedGuards[] = $guard->setParams($pathParams);
            }
        }
        return $matchedGuards;
    }
}
