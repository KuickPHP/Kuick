<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Config;

/**
 * Listener validator
 */
class ListenerValidator
{
    public function __construct(ListenerConfig $listenerConfig)
    {
        $this->validatePattern($listenerConfig);
        $this->validateCallable($listenerConfig);
    }

    private function validateCallable(ListenerConfig $listenerConfig): void
    {
        //callable empty
        if (empty($listenerConfig->callable)) {
            throw new ConfigException('Listener callable is empty');
        }
        //inexistent class
        if (!class_exists($listenerConfig->callable)) {
            throw new ConfigException('Callable: ' . $listenerConfig->callable . '" does not exist');
        }
        //inexistent __invoke() method
        if (!method_exists($listenerConfig->callable, '__invoke')) {
            throw new ConfigException('Callable ' . $listenerConfig->callable . ' is not invokable');
        }
    }

    private function validatePattern(ListenerConfig $listenerConfig): void
    {
        //pattern is not a string
        if (empty($listenerConfig->pattern)) {
            throw new ConfigException('Listener pattern is empty');
        }
    }
}
