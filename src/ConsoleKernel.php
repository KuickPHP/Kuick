<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework;

use Kuick\Framework\Config\ConfigIndexer;
use Kuick\Framework\Events\KernelCreatedEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;

/**
 * Console application Kernel
 */
final class ConsoleKernel extends KernelAbstract
{
    public function __construct(string $projectDir)
    {
        parent::__construct($projectDir);
        $logger = $this->getContainer()->get(LoggerInterface::class);
        $configIndexer = $this->getContainer()->get(ConfigIndexer::class);
        // adding commands to console application
        foreach ($configIndexer->getConfigFilePaths(ConfigIndexer::COMMANDS_FILE_SUFFIX) as $commandConfigFile) {
            foreach (require $commandConfigFile as $commandConfig) {
                $logger->debug('Adding command: ' . $commandConfig->name);
                $command = $this->getContainer()->get($commandConfig->commandClassName);
                $command->setName($commandConfig->name);
                $command->setDescription($commandConfig->description);
                $this->getContainer()->get(Application::class)->add($command);
            }
        }
        $logger->info('Console application initialized');
        // dispatching KernelCreatedEvent
        $this->getContainer()->get(EventDispatcherInterface::class)->dispatch(new KernelCreatedEvent($this));
    }
}
