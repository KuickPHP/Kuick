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
 * Guard validator
 */
class GuardValidator
{
    public function __construct(Guard $guard)
    {
        $this->validatePath($guard);
        $this->validateMethods($guard);
        $this->validateController($guard);
    }

    private function validatePath(Guard $guard): void
    {
        //path is not a string
        if (empty($guard->path)) {
            throw new ConfigException('Guard path is empty');
        }
        try {
            //@TODO: test path against the regex pattern
            //test against empty string
            //preg_match(sprintf(Router::MATCH_PATTERN, $route->path), '');
        } catch (Throwable $error) {
            //throw new RouterException('Route path invalid: ' . $route->path . ', ' . $error->getMessage());
        }
    }

    private function validateMethods(Guard $guard): void
    {
        foreach ($guard->methods as $method) {
            //method not a standard HTTP method
            if (!in_array($method, [
                Guard::METHOD_GET,
                Guard::METHOD_OPTIONS,
                Guard::METHOD_POST,
                Guard::METHOD_PUT,
                Guard::METHOD_PATCH,
                Guard::METHOD_DELETE,
            ])) {
                throw new ConfigException('Guard method invalid, path: ' . $guard->path . ', method: ' . $method);
            }
        }
    }

    private function validateController(Guard $guard): void
    {
        //action not defined
        if (empty($guard->guardClassName)) {
            throw new ConfigException('Guard is missing controller class name, path: ' . $guard->path);
        }
        //inexistent class
        if (!class_exists($guard->guardClassName)) {
            throw new ConfigException('Guard class: ' . $guard->guardClassName . '" does not exist, path: ' . $guard->path);
        }
        //inexistent __invoke() method
        if (!method_exists($guard->guardClassName, '__invoke')) {
            throw new ConfigException('Guard class: ' . $guard->guardClassName . '" is not invokable, path: ' . $guard->path);
        }
    }
}
