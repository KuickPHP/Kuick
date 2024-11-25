<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Example\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:kuick:hello', description: 'Says hello')]
class HelloCommand extends Command
{
    private const MESSAGE_TEMPLATE = 'Kuick says: Hello %s!';
    private const DEFAULT_NAME = 'my friend';

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::OPTIONAL, 'Who do you want to greet?');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name') ?? self::DEFAULT_NAME;
        $output->writeln(sprintf(self::MESSAGE_TEMPLATE, $name));
        return 0;
    }
}
