<?php

namespace Tests\Unit\App\DependencyInjection\Factories;

use DI\ContainerBuilder;
use Kuick\Framework\DependencyInjection\Factories\LoggerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers Kuick\Framework\DependencyInjection\Factories\LoggerFactory
 */
class LoggerFactoryTest extends TestCase
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
        (new LoggerFactory())->build($builder);
        $container = $builder->build();
        $this->assertInstanceOf(LoggerInterface::class, $container->get(LoggerInterface::class));
    }
}
