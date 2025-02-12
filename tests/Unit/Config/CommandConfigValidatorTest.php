<?php

namespace Tests\Unit\Kuick\Framework\Config;

use Kuick\Framework\Config\ConfigException;
use Kuick\Framework\Config\CommandConfig;
use Kuick\Framework\Config\CommandConfigValidator;
use Tests\Unit\Kuick\Framework\Mocks\MockCommand;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers Kuick\Framework\Config\CommandConfigValidator
 */
class CommandConfigValidatorTest extends TestCase
{
    public function testIfCorrectCommandConfigValidatorDoesNothing(): void
    {
        $commandConfig = new CommandConfig('/test', MockCommand::class);
        (new CommandConfigValidator())->validate($commandConfig);
        $this->assertTrue(true);
    }

    public function testIfEmptyPathRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Command name should not be empty');
        (new CommandConfigValidator())->validate(new CommandConfig('', MockCommand::class));
    }

    public function testIfEmptyCommandClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Command class name should not be empty, name: /test');
        (new CommandConfigValidator())->validate(new CommandConfig('/test', ''));
    }

    public function testIfInexistentCommandClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Command class: "InexistentCommand" does not exist, name: /test');
        (new CommandConfigValidator())->validate(new CommandConfig('/test', 'InexistentCommand'));
    }

    public function testIfNotInvokableCommandClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Command does not extend Command, name: /test');
        (new CommandConfigValidator())->validate(new CommandConfig('/test', 'stdClass'));
    }
}
