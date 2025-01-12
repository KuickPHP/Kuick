<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use FilesystemIterator;
use GlobIterator;
use Symfony\Component\Console\Application;

/**
 * Console Application Kernel
 */
final class ConsoleKernel extends KernelAbstract
{
    private const APP_NAME_KEY = 'kuick.app.name';
    private const COMMAND_PATH_PATTERN = '/config/*.commands.php';

    public function __invoke(): void
    {
        //create a new application
        $application = new Application($this->getContainer()->get(self::APP_NAME_KEY));
        //adding commands
        foreach (new GlobIterator($this->projectDir . self::COMMAND_PATH_PATTERN, FilesystemIterator::KEY_AS_FILENAME) as $commandFile) {
            foreach (include $commandFile as $commandClass) {
                $application->add($this->getContainer()->get($commandClass));
            }
        }
        ini_set('max_execution_time', 0);
        $application->run();
    }
}
