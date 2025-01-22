<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Framework\DependencyInjection;

use DI\ContainerBuilder;
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
        $this->builder->addDefinitions([Application::class => function (ContainerInterface $container, LoggerInterface $logger, SystemCacheInterface $cache): Application {
            $consoleApplication = new Application($container->get(KernelInterface::DI_APP_NAME_KEY));
            foreach ((new ConfigIndexer($cache, $logger))->getConfigFiles($container->get(Kernel::DI_PROJECT_DIR_KEY), ConsoleApplicationBuilder::CONFIG_SUFFIX) as $commandsFile) {
                $commands = include $commandsFile;
                foreach ($commands as $commandClassName) {
                    if (!is_string($commandClassName)) {
                        throw new ConfigException('Command class name must be a string');
                    }
                    if (!(class_exists($commandClassName))) {
                        throw new ConfigException('Command class does not exist: ' . $commandClassName);
                    }
                    $command = $container->get($commandClassName);
                    if (!($command instanceof Command)) {
                        throw new ConfigException('Command must implement: ' . Command::class);
                    }
                    $logger->info('Adding command: ' . $commandClassName);
                    $consoleApplication->add($command);
                }
            }
            return $consoleApplication;
        }]);
    }
}
