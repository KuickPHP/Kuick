<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\Config;

use Kuick\Http\Message\RequestInterface;
use Throwable;

/**
 * Route validator
 */
class RouteValidator
{
    private const MATCH_PATTERN = '#^%s$#';

    public function __construct(RouteConfig $routeConfig)
    {
        $this->validatePath($routeConfig);
        $this->validateMethods($routeConfig);
        $this->validateController($routeConfig);
    }

    private function validatePath(RouteConfig $routeConfig): void
    {
        //path is not a string
        if (empty($routeConfig->path)) {
            throw new ConfigException('Route path should not be empty');
        }
        try {
            //test against empty string
            preg_match(sprintf(self::MATCH_PATTERN, $routeConfig->path), '');
        } catch (Throwable $error) {
            throw new ConfigException('Route path should be a valid regex pattern: ' . $routeConfig->path);
        }
    }

    private function validateMethods(RouteConfig $routeConfig): void
    {
        //limited to standard HTTP methods except HEAD and OPTIONS
        foreach ($routeConfig->methods as $method) {
            if (
                !in_array($method, [
                RequestInterface::METHOD_GET,
                RequestInterface::METHOD_POST,
                RequestInterface::METHOD_PUT,
                RequestInterface::METHOD_PATCH,
                RequestInterface::METHOD_DELETE,
                ])
            ) {
                throw new ConfigException('Route method: ' . $method . ' is invalid, path: ' . $routeConfig->path);
            }
        }
    }

    private function validateController(RouteConfig $routeConfig): void
    {
        //action not defined
        if (empty($routeConfig->controllerClassName)) {
            throw new ConfigException('Route controller class name should not be empty, path: ' . $routeConfig->path);
        }
        //inexistent class
        if (!class_exists($routeConfig->controllerClassName)) {
            throw new ConfigException('Route controller class: "' . $routeConfig->controllerClassName . '" does not exist, path: ' . $routeConfig->path);
        }
        //inexistent __invoke() method
        if (!method_exists($routeConfig->controllerClassName, '__invoke')) {
            throw new ConfigException('Route controller class: "' . $routeConfig->controllerClassName . '" is not invokable, path: ' . $routeConfig->path);
        }
        //@TODO: validate __invoke() method parameters
    }
}
