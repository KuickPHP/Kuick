<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Config;

use Kuick\Http\Message\RequestInterface;
use Throwable;

/**
 * Guard validator
 */
class GuardValidator
{
    public function __construct(GuardConfig $guardConfig)
    {
        $this->validatePath($guardConfig);
        $this->validateMethods($guardConfig);
        $this->validateController($guardConfig);
    }

    private function validatePath(GuardConfig $guardConfig): void
    {
        //path is not a string
        if (empty($guardConfig->path)) {
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

    private function validateMethods(GuardConfig $guardConfig): void
    {
        foreach ($guardConfig->methods as $method) {
            //method not a standard HTTP method
            if (!in_array($method, [
                RequestInterface::METHOD_GET,
                RequestInterface::METHOD_OPTIONS,
                RequestInterface::METHOD_POST,
                RequestInterface::METHOD_PUT,
                RequestInterface::METHOD_PATCH,
                RequestInterface::METHOD_DELETE,
            ])) {
                throw new ConfigException('Guard method invalid, path: ' . $guardConfig->path . ', method: ' . $method);
            }
        }
    }

    private function validateController(GuardConfig $guardConfig): void
    {
        //action not defined
        if (empty($guardConfig->guardClassName)) {
            throw new ConfigException('Guard is missing controller class name, path: ' . $guardConfig->path);
        }
        //inexistent class
        if (!class_exists($guardConfig->guardClassName)) {
            throw new ConfigException('Guard class: ' . $guardConfig->guardClassName . '" does not exist, path: ' . $guardConfig->path);
        }
        //inexistent __invoke() method
        if (!method_exists($guardConfig->guardClassName, '__invoke')) {
            throw new ConfigException('Guard class: ' . $guardConfig->guardClassName . '" is not invokable, path: ' . $guardConfig->path);
        }
        //@TODO: validate __invoke() method parameters
    }
}
