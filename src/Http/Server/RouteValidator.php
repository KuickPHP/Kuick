<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http\Server;

use Throwable;

/**
 * Route validator
 */
class RouteValidator
{
    public function __construct(Route $route)
    {
        $this->validatePath($route);
        $this->validateMethod($route);
        $this->validateController($route);
        $this->validateMiddleware($route);
    }

    private function validatePath(Route $route): void
    {
        //path is not a string
        if (empty($route->path)) {
            throw new RouterException('Route path is empty');
        }
        try {
            //test against empty string
            preg_match(sprintf(Router::MATCH_PATTERN, $route->path), '');
        } catch (Throwable $error) {
            throw new RouterException('Route path invalid: ' . $route->path . ', ' . $error->getMessage());
        }
    }

    private function validateMethod(Route $route): void
    {
        //method name invalid
        if (!in_array($route->method, [
            Route::METHOD_GET,
            Route::METHOD_POST,
            Route::METHOD_PUT,
            Route::METHOD_PATCH,
            Route::METHOD_DELETE,
        ])) {
            throw new RouterException('Route method invalid, path: ' . $route->path);
        }
    }

    private function validateController(Route $route): void
    {
        //action not defined
        if (empty($route->controller)) {
            throw new RouterException('Route is missing controller class name, path: ' . $route->path);
        }
        //method defined but not a string
        if (!is_string($route->controller)) {
            throw new RouterException('Route controller class name is not a string, path: ' . $route->path);
        }
        //inexistent class
        if (!class_exists($route->controller)) {
            throw new RouterException('Route controller: ' . $route->controller . '" does not exist, path: ' . $route->path);
        }
        //inexistent __invoke() method
        if (!method_exists($route->controller, '__invoke')) {
            throw new RouterException('Route controller: ' . $route->controller . '" is not invokable, path: ' . $route->path);
        }
    }

    private function validateMiddleware(Route $route): void
    {
        //optional middlewares
        if (empty($route->middlewares)) {
            return;
        }
        //validating each guard
        foreach ($route->middlewares as $middleware) {
            //guard is not a string
            if (!is_string($middleware)) {
                throw new RouterException('Middleware class name is not a string, path: ' . $route->path);
            }
            //inexistent class
            if (!class_exists($middleware)) {
                throw new RouterException('Middleware: "' . $middleware . '" does not exist, path: ' . $route->path);
            }
            //inexistent __invoke() method
            if (!method_exists($middleware, '__invoke')) {
                throw new RouterException('Middleware: "' . $middleware . '" is not invokable, path: ' . $route->path);
            }
        }
    }
}
