<?php

namespace Tests\Unit\Kuick\Framework\Config;

use Kuick\Framework\Config\CommandConfig;
use Kuick\EventDispatcher\CommandPriority;
use Tests\Unit\Kuick\Framework\Mocks\MockCommand;
use PHPUnit\Framework\TestCase;

/**
 * @covers Kuick\Framework\Config\CommandConfig
 */
class CommandConfigTest extends TestCase
{
    public function testIfCommandConfigIsDefinedWithTheDefaultMethods(): void
    {
        $commandConfig = new CommandConfig('name', MockCommand::class, 'description');
        $this->assertEquals('name', $commandConfig->name);
        $this->assertEquals(MockCommand::class, $commandConfig->commandClassName);
        $this->assertEquals('description', $commandConfig->description);
    }
}
