<?php

namespace Kuick\Tests\App\DIFactories;

use DI\ContainerBuilder;
use Kuick\App\DIFactories\BuildLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;

use function PHPUnit\Framework\assertFileExists;
use function PHPUnit\Framework\assertTrue;

/**
 * @covers \Kuick\App\DIFactories\BuildLogger
 * @covers \Kuick\App\DIFactories\FactoryAbstract
 */
class BuildLoggerTest extends TestCase
{
    public static string $projectDir;

    public static function setUpBeforeClass(): void
    {
        $fsys = new Filesystem();
        self::$projectDir = dirname(__DIR__) . '/../../Mocks/MockProjectDir';
        $logDir = self::$projectDir . '/var/log';
        $fsys->remove($logDir);
        $fsys->mkdir($logDir);
    }

    public function testIfMinimalConfigProducesALogger(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            'kuick.app.name' => 'test',
            'kuick.app.timezone' => 'Europe/Paris',
            'kuick.app.monolog.usemicroseconds' => false,
            'kuick.app.monolog.handlers' => [

            ],
            'kuick.app.monolog.level' => 'NOTICE',

        ]);
        (new BuildLogger($builder))();
        $container = $builder->build();

        $logger = $container->get(LoggerInterface::class);
        $logger->error('test');
        assertTrue(true);
    }

    public function testIfAddedStreamHandlerWritesTheLog(): void
    {
        $builder = new ContainerBuilder();
        $logfile = self::$projectDir . '/var/log/testing.log';
        $builder->addDefinitions([
            'kuick.app.name' => 'test',
            'kuick.app.timezone' => 'Europe/Paris',
            'kuick.app.monolog.usemicroseconds' => true,
            'kuick.app.monolog.handlers' => [
                ['type' => 'stream', 'path' => $logfile]
            ],
            'kuick.app.monolog.level' => 'NOTICE',
        ]);
        (new BuildLogger($builder))();
        $container = $builder->build();
        $logger = $container->get(LoggerInterface::class);
        $logger->error('test');
        assertFileExists($logfile);
    }
}
