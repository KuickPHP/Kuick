<?php

namespace Tests\Kuick\Mocks;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

#[AsCommand(name: 'app:kuick:ping', description: 'Says hello')]
class CommandMock extends Command
{
}
