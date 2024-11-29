<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Services;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;

/**
 *
 */
class BuildConsoleApplication extends ServiceBuildAbstract
{
    public function __invoke(): void
    {
        $this->builder->addDefinitions([Application::class => function (ContainerInterface $container): Application {
            $commands = [];
            //app commands (normal priority)
            foreach (glob(BASE_PATH . '/etc/*.commands.php') as $commandFile) {
                $commands = array_merge($commands, include $commandFile);
            }
            $application = new Application($container->get('kuick.app.name'));
            foreach ($commands as $commandClass) {
                $application->add($container->get($commandClass));
            }
            return $application;
        }]);
    }
}
