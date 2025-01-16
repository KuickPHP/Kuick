<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http\Server;

use Kuick\Http\MethodNotAllowedException;
use Kuick\Http\NotFoundException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/**
 * Action router
 */
class Router
{
    public const MATCH_PATTERN = '#^%s$#';
    private array $routes = [];

    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * @param Route[] $routes
     */
    public function setRoutes(array $routes): self
    {
        $this->routes = $routes;
        return $this;
    }

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @throws NotFoundException
     * @throws MethodNotAllowedException
     */
    public function matchRoute(ServerRequestInterface $request): RouteMatch
    {
        $requestMethod = $request->getMethod();
        $mismatchedMethod = null;
        /**
         * @var Route $route
         */
        foreach ($this->routes as $route) {
            //trim right slash
            $requestPath = $request->getUri()->getPath() == '/' ? '/' : rtrim($request->getUri()->getPath(), '/');
            $this->logger->debug('Trying route: ' . $route->method . ' ' . $route->path);
            //matching route
            $results = [];
            $matchResult = preg_match('#^' . $route->path . '$#', $requestPath, $results);
            if (!$matchResult) {
                continue;
            }
            //methods are matching or HEAD to GET route
            if ($requestMethod == $route->method || ($requestMethod == Route::METHOD_HEAD && $route->method == Route::METHOD_GET)) {
                $this->logger->debug('Matched route: ' . $route->path . ' ' . $route->path);
                return new RouteMatch($route, $this->parseRouteParams($results));
            }
            //method mismatch
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
