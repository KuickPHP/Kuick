<?php

namespace Tests\Kuick\App\DIFactories;

use DI\ContainerBuilder;
use Kuick\App\DIFactories\BuildRouteMatcher;
use Kuick\App\Router\RouteMatcher;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Filesystem\Filesystem;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\App\DIFactories\BuildRouteMatcher
 * @covers \Kuick\App\DIFactories\FactoryAbstract
 */
class BuildRouteMatcherTest extends TestCase
{
    public static string $projectDir;

    public static function setUpBeforeClass(): void
    {
        $fs = new Filesystem();
        self::$projectDir = dirname(__DIR__) . '/../../Mocks/MockProjectDir';
        $cacheFiles = self::$projectDir . '/var/cache/kuick-app-routematcher-routes';
        $fs->remove($cacheFiles);
    }

    public function testIfRoutesAreProperlyBuilt(): void
    {
        $container = $this->getContainer();
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
        putenv('KUICK_APP_ENV=prod');
        //first build - create cache
        $container = $this->getContainer();
        $rm = $container->get(RouteMatcher::class);
        assertCount(2, $rm->getRoutes());
    }

    private function getContainer(): ContainerInterface
    {
        //cached from
        $cb = new ContainerBuilder();
        $cb->addDefinitions([
            'kuick.app.env' => 'prod',
            'kuick.app.project.dir' => self::$projectDir,
            LoggerInterface::class => new NullLogger(),
        ]);
        (new BuildRouteMatcher($cb))();
        return $cb->build();
    }
}
