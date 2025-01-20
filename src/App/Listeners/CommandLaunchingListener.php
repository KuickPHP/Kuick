<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Listeners;

use FilesystemIterator;
use GlobIterator;
use Kuick\App\Events\CommandReceivedEvent;
use Kuick\App\KernelInterface;
use Symfony\Component\Console\Application;

final class CommandLaunchingListener
{
    private const COMMAND_PATH_PATTERN = '/config/*.commands.php';

    public function __invoke(CommandReceivedEvent $commandReceivedEvent): void
    {
        $kernel = $commandReceivedEvent->getKernel();
        $container = $kernel->getContainer();
        //create a new application
        $application = new Application($container->get(KernelInterface::DI_APP_NAME_KEY));
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
