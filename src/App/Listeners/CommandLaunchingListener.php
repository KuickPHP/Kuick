<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Listeners;

use FilesystemIterator;
use GlobIterator;
use Kuick\App\Events\CommandReceived;
use Symfony\Component\Console\Application;

class CommandLaunchingListener
{
    private const APP_NAME_KEY = 'kuick.app.name';
    private const COMMAND_PATH_PATTERN = '/config/*.commands.php';

    public function __invoke(CommandReceived $commandArriver): void
    {
        $kernel = $commandArriver->getKernel();
        $container = $kernel->getContainer();
        //create a new application
        $application = new Application($container->get(self::APP_NAME_KEY));
        //adding commands
        foreach (new GlobIterator($kernel->projectDir . self::COMMAND_PATH_PATTERN, FilesystemIterator::KEY_AS_FILENAME) as $commandFile) {
            foreach (include $commandFile as $commandClass) {
                $application->add($container->get($commandClass));
            }
        }
        ini_set('max_execution_time', 0);
        $application->run();
    }
}
