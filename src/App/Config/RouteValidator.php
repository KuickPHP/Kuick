<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Config;

use Throwable;

/**
 * Route validator
 */
class RouteValidator
{
    private const MATCH_PATTERN = '#^%s$#';

    public function __construct(Route $route)
    {
        $this->validatePath($route);
        $this->validateMethods($route);
        $this->validateController($route);
    }

    private function validatePath(Route $route): void
    {
        //path is not a string
        if (empty($route->path)) {
            throw new ConfigException('Route path is empty');
        }
        try {
            //test against empty string
            preg_match(sprintf(self::MATCH_PATTERN, $route->path), '');
        } catch (Throwable $error) {
            throw new ConfigException('Route path invalid: ' . $route->path . ', ' . $error->getMessage());
        }
    }

    private function validateMethods(Route $route): void
    {
        //limited to standard HTTP methods except HEAD and OPTIONS
        foreach ($route->methods as $method) {
            if (!in_array($method, [
                Route::METHOD_GET,
                Route::METHOD_POST,
                Route::METHOD_PUT,
                Route::METHOD_PATCH,
                Route::METHOD_DELETE,
            ])) {
                throw new ConfigException('Route method invalid, path: ' . $route->path);
            }
        }
    }

    private function validateController(Route $route): void
    {
        //action not defined
        if (empty($route->controllerClassName)) {
            throw new ConfigException('Route is missing controller class name, path: ' . $route->path);
        }
        //inexistent class
        if (!class_exists($route->controllerClassName)) {
            throw new ConfigException('Route controller: ' . $route->controllerClassName . '" does not exist, path: ' . $route->path);
        }
        //inexistent __invoke() method
        if (!method_exists($route->controllerClassName, '__invoke')) {
            throw new ConfigException('Route controller: ' . $route->controllerClassName . '" is not invokable, path: ' . $route->path);
        }
    }
}
