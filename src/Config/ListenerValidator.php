<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\Config;

/**
 * Listener validator
 */
class ListenerValidator implements ConfigValidatorInterface
{
    public function validate(object $configObject): void
    {
        if (!$configObject instanceof ListenerConfig) {
            throw new ConfigException('ListenerConfig object expected');
        }
        $this->validatePattern($configObject);
        $this->validateCallable($configObject);
    }

    private function validateCallable(ListenerConfig $listenerConfig): void
    {
        //callable empty
        if (empty($listenerConfig->listenerClassName)) {
            throw new ConfigException('Listener class name should not be empty');
        }
        //inexistent class
        if (!class_exists($listenerConfig->listenerClassName)) {
            throw new ConfigException('Listener class: "' . $listenerConfig->listenerClassName . '" does not exist');
        }
        //inexistent __invoke() method
        if (!method_exists($listenerConfig->listenerClassName, '__invoke')) {
            throw new ConfigException('Listener class: "' . $listenerConfig->listenerClassName . '" is not invokable');
        }
    }

    private function validatePattern(ListenerConfig $listenerConfig): void
    {
        //pattern is not a string
        if (empty($listenerConfig->pattern)) {
            throw new ConfigException('Listener pattern should not be empty');
        }
    }
}
