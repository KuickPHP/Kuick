<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use Symfony\Component\Console\Application;

/**
 * Console Application Kernel
 */
final class ConsoleKernel extends KernelAbstract
{
    private Application $application;

    public function __construct()
    {
        parent::__construct();
        //create a new application
        $this->application = new Application($this->container->get('kuick.app.name'));
        //adding commands
        foreach (glob($this->getProjectDir() . '/etc/*.commands.php') as $commandFile) {
            foreach (include $commandFile as $commandClass) {
                $this->application->add($this->container->get($commandClass));
            }
        }
        ini_set('max_execution_time', 0);
    }

    public function __invoke(): void
    {
        $this->application->run();
    }
}
