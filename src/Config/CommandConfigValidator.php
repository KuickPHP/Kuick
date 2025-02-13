<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\Config;

use Symfony\Component\Console\Command\Command;

/**
 * Command config validator
 */
final class CommandConfigValidator
{
    public function validate(CommandConfig $configObject): void
    {
        $this->validateName($configObject);
        $this->validateCommand($configObject);
    }

    private function validateName(CommandConfig $commandConfig): void
    {
        //path is not a string
        if (empty($commandConfig->name)) {
            throw new ConfigException('Command name should not be empty');
        }
    }

    private function validateCommand(CommandConfig $commandConfig): void
    {
        //action not defined
        if (empty($commandConfig->commandClassName)) {
            throw new ConfigException('Command class name should not be empty, name: ' . $commandConfig->name);
        }
        //inexistent class
        if (!class_exists($commandConfig->commandClassName)) {
            throw new ConfigException('Command class: "' . $commandConfig->commandClassName . '" does not exist, name: ' . $commandConfig->name);
        }
        //not a subclass of command
        if (!is_subclass_of($commandConfig->commandClassName, Command::class)) {
            throw new ConfigException('Command does not extend Command, name: ' . $commandConfig->name);
        }
    }
}
