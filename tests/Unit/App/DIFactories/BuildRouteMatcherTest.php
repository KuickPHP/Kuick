<?php

namespace Tests\Kuick\App\DIFactories;

use DI\ContainerBuilder;
use Kuick\App\DIFactories\BuildRouteMatcher;
use Kuick\App\Router\RouteMatcher;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Filesystem\Filesystem;

use function PHPUnit\Framework\assertCount;
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
        $fakerootVar = dirname(__DIR__) . '/../../Mocks/FakeRoot/var/cache';
        $sfs = new Filesystem();
        $sfs->remove($fakerootVar);
        $sfs->mkdir($fakerootVar);
    }

    protected function tearDown(): void
    {
        $fakerootVar = dirname(__DIR__) . '/../../Mocks/FakeRoot/var';
        $sfs = new Filesystem();
        $sfs->remove($fakerootVar);
    }

    public function testIfRoutesAreProperlyBuilt(): void
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
        assertEquals([
            [
                'path' => '/hello/(?<userId>[0-9]{1,12})',
                'controller' => 'Tests\Kuick\Mocks\ControllerMock',
                'arguments' => [
                    'Tests\Kuick\Mocks\ControllerMock' => [
                        'userId' => [
                            'type' => 'int',
                            'isOptional' => false,
                            'default' => null,
                        ],
                    ],
                ],
            ],
            [
                'method' => 'POST',
                'path' => '/',
                'controller' => 'Tests\Kuick\Mocks\RequestDependentControllerMock',
                'arguments' =>  [
                    'Tests\Kuick\Mocks\RequestDependentControllerMock' => [
                        'request' => [
                            'type' => 'Psr\Http\Message\ServerRequestInterface',
                            'isOptional' => false,
                            'default' => null,
                        ],
                    ],
                    'Tests\Kuick\Mocks\RequestDependentGuardMock' => [
                        'request' => [
                            'type' => 'Psr\Http\Message\ServerRequestInterface',
                            'isOptional' => false,
                            'default' => null,
                        ],
                    ],
                ],
                'guards' => ['Tests\Kuick\Mocks\RequestDependentGuardMock']
            ],
        ], $rm->getRoutes());
    }

    public function testIfCachedContainerWorks(): void
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
        assertCount(2, $rm->getRoutes());
    }
}
