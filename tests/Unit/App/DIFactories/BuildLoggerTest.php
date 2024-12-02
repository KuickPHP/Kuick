<?php

namespace Tests\Kuick\App\DIFactories;

use DI\ContainerBuilder;
use Kuick\App\AppDIContainerBuilder;
use Kuick\App\DIFactories\BuildLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

/**
 * @covers \Kuick\App\DIFactories\BuildLogger
 */
class BuildLoggerTest extends TestCase
{
    public function test(): void
    {
        $cb = new ContainerBuilder();
        $cb->addDefinitions([
            'kuick.app.name' => 'test',
            'kuick.app.timezone' => 'Europe/Paris',
            'kuick.app.monolog.usemicroseconds' => false,
            'kuick.app.monolog.handlers' => [

            ],
            'kuick.app.monolog.level' => 'NOTICE',

        ]);
        (new BuildLogger($cb))();
        $container = $cb->build();

        $logger = $container->get(LoggerInterface::class);
        $logger->error('test');
        assertTrue(true);
    }
}
