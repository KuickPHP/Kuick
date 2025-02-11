<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\Config;

use Closure;
use Kuick\Http\Message\RequestInterface;
use Throwable;

/**
 * Guard validator
 */
class GuardValidator implements ConfigValidatorInterface
{
    private const MATCH_PATTERN = '#^%s$#';

    public function validate(object $configObject): void
    {
        if (!$configObject instanceof GuardConfig) {
            throw new ConfigException('GuardConfig object expected');
        }
        $this->validatePath($configObject);
        $this->validateMethods($configObject);
        $this->validateController($configObject);
    }

    private function validatePath(GuardConfig $guardConfig): void
    {
        //path is not a string
        if (empty($guardConfig->path)) {
            throw new ConfigException('Guard path should not be empty');
        }
        try {
            //test against empty string
            preg_match(sprintf(self::MATCH_PATTERN, $guardConfig->path), '');
        } catch (Throwable) {
            throw new ConfigException('Guard path should be a valid regex pattern: ' . $guardConfig->path);
        }
    }

    private function validateMethods(GuardConfig $guardConfig): void
    {
        foreach ($guardConfig->methods as $method) {
            //method not a standard HTTP method
            if (
                !in_array($method, [
                RequestInterface::METHOD_GET,
                RequestInterface::METHOD_OPTIONS,
                RequestInterface::METHOD_POST,
                RequestInterface::METHOD_PUT,
                RequestInterface::METHOD_PATCH,
                RequestInterface::METHOD_DELETE,
                ])
            ) {
                throw new ConfigException('Guard method invalid, path: ' . $guardConfig->path . ', method: ' . $method);
            }
        }
    }

    private function validateController(GuardConfig $guardConfig): void
    {
        //action not defined
        if (empty($guardConfig->guard)) {
            throw new ConfigException('Guard class name should not be empty, path: ' . $guardConfig->path);
        }
        // inline closure
        // @TODO: validate callable parameters and return type
        if ($guardConfig->guard instanceof Closure) {
            return;
        }
        //inexistent class
        if (!class_exists($guardConfig->guard)) {
            throw new ConfigException('Guard class: "' . $guardConfig->guard . '" does not exist, path: ' . $guardConfig->path);
        }
        //inexistent __invoke() method
        if (!method_exists($guardConfig->guard, '__invoke')) {
            throw new ConfigException('Guard class: "' . $guardConfig->guard . '" is not invokable, path: ' . $guardConfig->path);
        }
        //@TODO: validate __invoke() method parameters
    }
}
