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
    public function __construct(Listener $listener)
    {
        $this->validatePattern($listener);
        $this->validateCallable($listener);
    }

    private function validateCallable(Listener $listener): void
    {
        //callable empty
        if (empty($listener->callable)) {
            throw new ConfigException('Listener callable is empty');
        }
        //inexistent class
        if (!class_exists($listener->callable)) {
            throw new ConfigException('Callable: ' . $listener->callable . '" does not exist');
        }
        //inexistent __invoke() method
        if (!method_exists($listener->callable, '__invoke')) {
            throw new ConfigException('Callable ' . $listener->callable . ' is not invokable');
        }
    }

    private function validatePattern(Listener $listener): void
    {
        //pattern is not a string
        if (empty($listener->pattern)) {
            throw new ConfigException('Listener pattern is empty');
        }
    }
}
