<?php

namespace Tests\Kuick\App\DIFactories;

use DI\ContainerBuilder;
use Kuick\App\DIFactories\BuildLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

use function PHPUnit\Framework\assertFileExists;
use function PHPUnit\Framework\assertTrue;

/**
 * @covers \Kuick\App\DIFactories\BuildLogger
 */
class BuildLoggerTest extends TestCase
{
    private const LOG_FILE = '/../../Mocks/FakeRoot/var/testing.log';
    protected function tearDown(): void
    {
        $logfile = dirname(__DIR__) . self::LOG_FILE;
        file_exists($logfile) && unlink($logfile);
    }

    public function testIfMinimalConfigProducesALogger(): void
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

    public function testIfAddedStreamHandlerWritesTheLog(): void
    {
        $cb = new ContainerBuilder();
        $logfile = dirname(__DIR__) . self::LOG_FILE;
        $cb->addDefinitions([
            'kuick.app.name' => 'test',
            'kuick.app.timezone' => 'Europe/Paris',
            'kuick.app.monolog.usemicroseconds' => true,
            'kuick.app.monolog.handlers' => [
                ['type' => 'stream', 'path' => $logfile]
            ],
            'kuick.app.monolog.level' => 'NOTICE',
        ]);
        (new BuildLogger($cb))();
        $container = $cb->build();
        $logger = $container->get(LoggerInterface::class);
        $logger->error('test');
        assertFileExists($logfile);
    }
}
