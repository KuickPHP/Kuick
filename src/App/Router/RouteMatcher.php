<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Router;

use Kuick\Http\MethodNotAllowedException;
use Kuick\Http\NotFoundException;
use Kuick\Http\RequestMethods;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/**
 *
 */
class RouteMatcher
{
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
        if (RequestMethods::OPTIONS == $request->getMethod()) {
            return [];
        }
        $requestMethod = $request->getMethod();
        $methodNotAllowedForRoute = null;
        foreach ($this->routes as $route) {
            $this->logger->debug('Trying route: ' . $route['pattern']);
            //matching route
            if (!preg_match('#^' . $route['pattern'] . '$#', $request->getUri()->getPath())) {
                continue;
            }
            //method defaults to GET
            $routeMethod = $route['method'] ?? RequestMethods::GET;
            //methods are matching or HEAD to GET route
            if ($requestMethod == $routeMethod || ($requestMethod == RequestMethods::HEAD && $routeMethod == RequestMethods::GET)) {
                $this->logger->debug('Found matching route: ' . $routeMethod . $route['pattern']);
                return $route;
            }
            //method mismatch
            $this->logger->debug('Method mismatch, but action matching pattern: ' . $route['pattern']);
            $methodNotAllowedForRoute = $route;
        }
        if (null !== $methodNotAllowedForRoute) {
            throw new MethodNotAllowedException($requestMethod . ' method is not allowed for ' . $methodNotAllowedForRoute['pattern'] . ' route');
        }
        throw new NotFoundException('Action not found');
    }
}
