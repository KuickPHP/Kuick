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

    public function setRoutes(array $routes): self
    {
        $this->routes = $routes;
        return $this;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function findRoute(ServerRequestInterface $request): array
    {
        //empty route for OPTIONS
        if ('OPTIONS' == $request->getMethod()) {
            return [];
        }
        $requestMethod = $request->getMethod();
        $mismatchedMethod = null;
        foreach ($this->routes as $route) {
            //method defaults to GET
            $routeMethod = $route['method'] ?? 'GET';
            //trim right slash
            $requestPath = $request->getUri()->getPath() == '/' ? '/' : rtrim($request->getUri()->getPath(), '/');
            $this->logger->debug('Trying route: ' . $routeMethod . ' ' . $route['path']);
            //matching route
            $results = [];
            $matchResult = preg_match('#^' . $route['path'] . '$#', $requestPath, $results);
            if (!$matchResult) {
                continue;
            }
            $route['params'] = $this->parseRouteParams($results);
            //methods are matching or HEAD to GET route
            if ($requestMethod == $routeMethod || ($requestMethod == 'HEAD' && $routeMethod == 'GET')) {
                $this->logger->debug('Matched route: ' . $routeMethod . ' ' . $route['path']);
                return $route;
            }
            //method mismatch
            $this->logger->debug('Method mismatch, but action matching path: ' . $route['path']);
            $mismatchedMethod = $route;
        }
        if (null !== $mismatchedMethod) {
            throw new MethodNotAllowedException($requestMethod . ' method is not allowed for path: ' . $mismatchedMethod['path']);
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
