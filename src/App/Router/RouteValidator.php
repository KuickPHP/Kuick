<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Router;

use Kuick\Http\InternalServerErrorException;
use Kuick\Http\Request;

/**
 *
 */
class RouteValidator
{
    public function __invoke(array $route): void
    {
        $this->validatePattern($route);
        $this->validateMethod($route);
        $this->validateAction($route);
        $this->validateGuards($route);
    }

    private function validatePattern(array $route): void
    {
        //pattern missing
        if (!isset($route['pattern'])) {
            throw new InternalServerErrorException('One or more actions are missing pattern');
        }
        //pattern is not a string
        if (!is_string($route['pattern'])) {
            throw new InternalServerErrorException('One or more actions has invalid pattern');
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
            throw new InternalServerErrorException('Action: ' . $route['pattern'] . ' method is not a string');
        }
        //method name invalid
        if (!in_array($route['method'], Request::ALL_METHODS)) {
            throw new InternalServerErrorException('Action: ' . $route['pattern'] . ' method should be one of GET, POST, PUT, DELETE, PATCH, HEAD, OPTIONS, PURGE');
        }
    }

    private function validateAction(array $route): void
    {
        //action not defined
        if (!isset($route['action'])) {
            throw new InternalServerErrorException('Action: ' . $route['pattern'] . ' is missing action class name');
        }
        //method defined but not a string
        if (!is_string($route['action'])) {
            throw new InternalServerErrorException('Action: ' . $route['pattern'] . ' class name is not a string');
        }
        //inexistent class
        if (!class_exists($route['action'])) {
            throw new InternalServerErrorException('Action "' . $route['action'] . '" does not exist');
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
            throw new InternalServerErrorException('Action: ' . $route['pattern'] . ' guards malformed, not an array');
        }
        //validating each guard
        foreach ($route['guards'] as $guard) {
            $this->validateGuard($route, $guard);
        }
    }

    private function validateGuard(array $route, string $guard): void
    {
        //guard is not a string
        if (!is_string($guard)) {
            throw new InternalServerErrorException('Guard: ' . $route['pattern'] . ' guard class name is not a string');
        }
        //inexistent guard class
        if (!class_exists($guard)) {
            throw new InternalServerErrorException('Action: ' . $route['pattern'] . ' guard: "' . $guard . '" does not exist');
        }
    }
}
