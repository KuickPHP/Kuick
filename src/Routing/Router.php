<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Routing;

use Kuick\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/**
 * Action router
 */
class Router
{
    private const MATCH_PATTERN = '#^%s$#';

    private array $routes = [];

    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * @TODO: add support for inline callables
     */
    public function addRoute(string $path, callable $controller, array $methods = [RequestInterface::METHOD_GET]): self
    {
        $this->routes[] = new ExecutableRoute($path, $controller, $methods);
        return $this;
    }

    /**
     * @throws MethodMismatchedException
     */
    public function matchRoute(ServerRequestInterface $request): ?ExecutableRoute
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
            $routeMethods = in_array(RequestInterface::METHOD_GET, $route->methods) ? array_merge([RequestInterface::METHOD_HEAD, $route->methods], $route->methods) : $route->methods;
            $this->logger->debug('Trying route: ' . $route->path);
            //matching path
            $pathParams = [];
            $matchResult = preg_match(sprintf(self::MATCH_PATTERN, $route->path), $requestPath, $pathParams);
            if (!$matchResult) {
                continue;
            }
            //matching method
            if (in_array($requestMethod, $routeMethods)) {
                $this->logger->debug('Matched route: ' . $route->path . ' ' . $route->path);
                return $route->setParams($pathParams);
            }
            // method mismatch
            $mismatchedMethod = $route;
        }
        if (null !== $mismatchedMethod) {
            throw new MethodMismatchedException('Method not allowed: ' . $requestMethod . ' for path: ' . $mismatchedMethod->path);
        }
        return null;
    }
}
