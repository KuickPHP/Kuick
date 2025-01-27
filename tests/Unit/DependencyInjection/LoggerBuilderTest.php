<?php

namespace Tests\Unit\App\DependencyInjection;

use DI\ContainerBuilder;
use Kuick\Framework\DependencyInjection\LoggerBuilder;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers Kuick\Framework\DependencyInjection\LoggerBuilder
 */
class LoggerBuilderTest extends TestCase
{
    public function testLoggerIsBuilt(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            'kuick.app.name' => 'Testing App',
            'kuick.app.monolog.usemicroseconds' => false,
            'kuick.app.monolog.level' => 'WARNING',
            'kuick.app.timezone' => 'Europe/Warsaw',
            'kuick.app.monolog.handlers' => [
                ['type' => 'stream', 'path' => 'php://stdout', 'level' => 'debug'],
            ],
        ]);
        (new LoggerBuilder($builder))();
        $container = $builder->build();
        $this->assertInstanceOf(LoggerInterface::class, $container->get(LoggerInterface::class));
    }
}
