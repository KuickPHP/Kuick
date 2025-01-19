<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Routing;

use Kuick\Http\MethodNotAllowedException;
use Kuick\Http\NotFoundException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/**
 * Action router
 */
class Router
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

    private array $routes = [];

    public function __construct(private LoggerInterface $logger)
    {
    }

    public function addRoute(string $path, callable $controller, array $methods = [self::METHOD_GET]): self
    {
        $this->routes[] = new ExecutableRoute($path, $controller, $methods);
        return $this;
    }

    /**
     * @throws NotFoundException
     * @throws MethodNotAllowedException
     */
    public function matchRoute(ServerRequestInterface $request): ExecutableRoute
    {
        $requestMethod = $request->getMethod();
        $mismatchedMethod = null;
        /**
         * @var ExecutableRoute $route
         */
        foreach ($this->routes as $route) {
            //trim right slash
            $requestPath = $request->getUri()->getPath() == '/' ? '/' : rtrim($request->getUri()->getPath(), '/');
            //adding HEAD if GET is present
            $routeMethods = in_array(self::METHOD_GET, $route->methods) ? array_merge([self::METHOD_HEAD, $route->methods], $route->methods) : $route->methods;
            $this->logger->debug('Trying route: ' . $route->path);
            //matching path
            $results = [];
            $matchResult = preg_match(sprintf(self::MATCH_PATTERN, $route->path), $requestPath, $results);
            if (!$matchResult) {
                continue;
            }
            //matching method
            if (in_array($requestMethod, $routeMethods)) {
                $this->logger->debug('Matched route: ' . $route->path . ' ' . $route->path);
                return $route->addParams($this->parseRouteParams($results));
            }
            // method mismatch
            $this->logger->debug('Method mismatch, but action matching path: ' . $route->path);
            $mismatchedMethod = $route;
        }
        if (null !== $mismatchedMethod) {
            throw new MethodNotAllowedException($requestMethod . ' method is not allowed for path: ' . $mismatchedMethod->path);
        }
        throw new NotFoundException('Not found');
    }

    private function parseRouteParams(array $results): array
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
