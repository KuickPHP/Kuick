<?php

namespace Tests\Kuick\App\DIFactories;

use DI\ContainerBuilder;
use Kuick\App\DIFactories\BuildRouteMatcher;
use Kuick\App\Router\RouteMatcher;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFileExists;
use function PHPUnit\Framework\assertTrue;

/**
 * @covers \Kuick\App\DIFactories\BuildRouteMatcher
 */
class BuildRouteMatcherTest extends TestCase
{
    protected function setUp(): void
    {
        $fakerootVar = dirname(__DIR__) . '/../../Mocks/FakeRoot/var';
        if (!file_exists($fakerootVar)) {
            mkdir($fakerootVar, 0777, true);
        }
    }

    public function test(): void
    {
        $cb = new ContainerBuilder();
        $cb->addDefinitions([
            'kuick.app.env' => 'prod',
            'app.project.dir' => dirname(__DIR__) . '/../../Mocks/FakeRoot',
            LoggerInterface::class => new NullLogger(),
        ]);
        (new BuildRouteMatcher($cb))();
        $container = $cb->build();
        $rm = $container->get(RouteMatcher::class);
        assertEquals([], $rm->getRoutes());
    }
}
