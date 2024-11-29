<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Router;

use Kuick\Http\InternalServerErrorException;
use Throwable;

/**
 *
 */
class RouteValidator
{
    public function __invoke(array $route): void
    {
        $this->validatePath($route);
        $this->validateMethod($route);
        $this->validateController($route);
        $this->validateGuards($route);
    }

    private function validatePath(array $route): void
    {
        //path missing
        if (!isset($route['path'])) {
            throw new InternalServerErrorException('One or more actions are missing path');
        }
        //path is not a string
        if (!is_string($route['path'])) {
            throw new InternalServerErrorException('One or more actions has invalid path, should be a string');
        }
        try {
            //test against empty string
            preg_match(sprintf(RouteMatcher::MATCH_PATTERN, $route['path']), '');
        } catch (Throwable $error) {
            throw new InternalServerErrorException('Path invalid: ' . $route['path'] . ', ' . $error->getMessage());
        }
    }

    private function validateMethod(array $route): void
    {
        //optional method (defaults to GET)
        if (!isset($route['method'])) {
            return;
        }
        //method defined but not a string
        if (!is_string($route['method'])) {
            throw new InternalServerErrorException('Method is not a string, path: ' . $route['path']);
        }
        //method name invalid
        if (!in_array($route['method'], ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS', 'PURGE'])) {
            throw new InternalServerErrorException('Method invalid, path: ' . $route['path']);
        }
    }

    private function validateController(array $route): void
    {
        //action not defined
        if (!isset($route['controller'])) {
            throw new InternalServerErrorException('Missing controller class name, path: ' . $route['path']);
        }
        //method defined but not a string
        if (!is_string($route['controller'])) {
            throw new InternalServerErrorException('Controller class name is not a string, path: ' . $route['path']);
        }
        //inexistent class
        if (!class_exists($route['controller'])) {
            throw new InternalServerErrorException('Controller: ' . $route['controller'] . '" does not exist, path: ' . $route['path']);
        }
        //inexistent __invoke() method
        if (!method_exists($route['controller'], '__invoke')) {
            throw new InternalServerErrorException('Controller: ' . $route['controller'] . '" is missing __invoke() method, path: ' . $route['path']);
        }
    }

    private function validateGuards(array $route): void
    {
        //optional guards
        if (!isset($route['guards'])) {
            return;
        }
        //guards should be an array
        if (!is_array($route['guards'])) {
            throw new InternalServerErrorException('Guards malformed, not an array, path: ' . $route['path']);
        }
        //validating each guard
        foreach ($route['guards'] as $guard) {
            //guard is not a string
            if (!is_string($guard)) {
                throw new InternalServerErrorException('Guard class name is not a string, path: ' . $route['path']);
            }
            //inexistent class
            if (!class_exists($guard)) {
                throw new InternalServerErrorException('Guard: "' . $guard . '" does not exist, path: ' . $route['path']);
            }
            //inexistent __invoke() method
            if (!method_exists($guard, '__invoke')) {
                throw new InternalServerErrorException('Guard: "' . $guard . '" is missing __invoke() method, path: ' . $route['path']);
            }
        }
    }
}
