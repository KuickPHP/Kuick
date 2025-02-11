<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\DependencyInjection;

use DI\ContainerBuilder;
use Kuick\Framework\Config\CommandValidator;
use Kuick\Framework\Config\ConfigException;
use Kuick\Framework\Kernel;
use Kuick\Framework\KernelInterface;
use Kuick\Framework\SystemCacheInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

/**
 * Console Application Builder
 */
class ConsoleApplicationBuilder
{
    public const CONFIG_SUFFIX = 'commands';

    public function __construct(private ContainerBuilder $builder)
    {
    }

    public function __invoke(): void
    {
        $this->builder->addDefinitions([Application::class =>
        function (
            ConfigIndexer $configIndexer,
            ContainerInterface $container,
            LoggerInterface $logger,
        ): Application {
            $consoleApplication = new Application($container->get(KernelInterface::DI_APP_NAME_KEY));
            foreach ($configIndexer->getConfigFiles(ConsoleApplicationBuilder::CONFIG_SUFFIX, new CommandValidator()) as $commandsFile) {
                $commands = include $commandsFile;
                /**
                 * @var CommandConfig $commandConfig
                 */
                foreach ($commands as $commandConfig) {                    
                    $logger->debug('Adding command: ' . $commandConfig->name);
                    /**
                     * @var Command $command
                     */
                    $command = $container->get($commandConfig->command);
                    $command->setName($commandConfig->name);
                    $command->setDescription($commandConfig->description);
                    $consoleApplication->add($command);
                }
            }
            return $consoleApplication;
        }]);
    }
}
