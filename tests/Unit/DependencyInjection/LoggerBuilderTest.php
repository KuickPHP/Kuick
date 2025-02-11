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
        $builder->useAttributes(true);
        $builder->addDefinitions([
            'app.name' => 'Testing App',
            'app.log.usemicroseconds' => false,
            'app.log.level' => 'WARNING',
            'app.timezone' => 'Europe/Warsaw',
            'app.log.handlers' => [
                ['type' => 'stream', 'path' => 'php://stdout', 'level' => 'debug'],
            ],
        ]);
        (new LoggerBuilder($builder))();
        $container = $builder->build();
        $this->assertInstanceOf(LoggerInterface::class, $container->get(LoggerInterface::class));
    }
}
