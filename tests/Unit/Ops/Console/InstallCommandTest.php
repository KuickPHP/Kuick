<?php

namespace Kuick\Tests\Unit\Ops\Console;

use Kuick\Ops\Console\InstallCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class InstallCommandTest extends TestCase
{
    public function testExecute()
    {
        $application = new Application();
        $application->add(new InstallCommand());

        $command = $application->find('app:kuick:install');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Application installed properly', $output);
        $this->assertEquals(0, $commandTester->getStatusCode());
    }
}
