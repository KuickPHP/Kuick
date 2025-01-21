<?php

namespace Kuick\Tests\App\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Kuick\App\DependencyInjection\ContainerCreator;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @covers \Kuick\App\DependencyInjection\ContainerCreator
 */
class ContainerCreatorTest extends TestCase
{
    private static string $projectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(dirname(__DIR__) . '/../Mocks/project-dir');
        (new Filesystem())->remove(self::$projectDir . '/var/cache');
    }

    public function testIfContainerCreatorIsWellDefined(): void
    {
        $containerCreator = new ContainerCreator();
        $containerCreator(self::$projectDir);
        $this->assertInstanceOf(ContainerCreator::class, $containerCreator);
    }
}
