<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\DependencyInjection\Factories;

use DI\ContainerBuilder;
use Kuick\Framework\Config\CommandConfig;
use Kuick\Framework\Config\ConfigIndexer;
use Kuick\Framework\KernelInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

/**
 * Console Application factory
 */
class ConsoleApplicationFactory
{
    public function build(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            Application::class => function (
                ConfigIndexer $configIndexer,
                ContainerInterface $container,
                LoggerInterface $logger,
            ): Application {
                $consoleApplication = new Application($container->get(KernelInterface::DI_APP_NAME_KEY));
                foreach ($configIndexer->getConfigFilePaths(ConfigIndexer::COMMANDS_FILE_SUFFIX) as $commandFilePath) {
                    /**
                     * @var CommandConfig $commandConfig
                     */
                    foreach (require $commandFilePath as $commandConfig) {
                        $logger->debug('Adding command: ' . $commandConfig->name);
                        /**
                         * @var Command $command
                         */
                        $command = $container->get($commandConfig->commandClassName);
                        $command->setName($commandConfig->name);
                        $command->setDescription($commandConfig->description);
                        $consoleApplication->add($command);
                    }
                }
                $logger->debug('Console application initialized');
                return $consoleApplication;
            }
        ]);
    }
}
