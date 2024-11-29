<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Router;

use Kuick\App\AppException;
use ReflectionMethod;

/**
 *
 */
class ActionInvokeArgumentReflector
{
    private const METHOD_NAME = '__invoke';

    public function __invoke(string $className): array
    {
        if (!method_exists($className, self::METHOD_NAME)) {
            throw new AppException('Class not found: ' . $className);
        }
        $invokeMethodReflection = new ReflectionMethod($className, self::METHOD_NAME);
        $availableParams = [];
        foreach ($invokeMethodReflection->getParameters() as $methodParam) {
            $availableParams[$methodParam->getName()] = [
                'type' => $methodParam->getType()->getName(),
                'isOptional' => $methodParam->isOptional(),
                'default' => $methodParam->isDefaultValueAvailable() ? $methodParam->getDefaultValue() : null,
            ];
        }
        return $availableParams;
    }
}
