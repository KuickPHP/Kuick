<?php

namespace Kuick\Tests\App\DIFactories;

use DI\ContainerBuilder;
use Kuick\App\DIFactories\BuildRouter;
use Kuick\Http\Server\Router;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Filesystem\Filesystem;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\App\DIFactories\BuildRouter
 * @covers \Kuick\App\DIFactories\FactoryAbstract
 */
class BuildRouterTest extends TestCase
{
    public static string $projectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = dirname(__DIR__) . '/../../Mocks/MockProjectDir';
        $cacheFile = self::$projectDir . '/var/cache/kuick-app-routes.php';
        (new Filesystem())->remove($cacheFile);
    }

    public function testIfRoutesAreProperlyBuilt(): void
    {
        $container = $this->getContainer();
        $routeMatcher = $container->get(Router::class);
        assertEquals([
            [
                'path' => '/hello/(?<userId>[0-9]{1,12})',
                'controller' => 'Kuick\Tests\Mocks\ControllerMock',
                'arguments' => [
                    'Kuick\Tests\Mocks\ControllerMock' => [
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
                'controller' => 'Kuick\Tests\Mocks\RequestDependentControllerMock',
                'arguments' =>  [
                    'Kuick\Tests\Mocks\RequestDependentControllerMock' => [
                        'request' => [
                            'type' => 'Psr\Http\Message\ServerRequestInterface',
                            'isOptional' => false,
                            'default' => null,
                        ],
                    ],
                    'Kuick\Tests\Mocks\RequestDependentGuardMock' => [
                        'request' => [
                            'type' => 'Psr\Http\Message\ServerRequestInterface',
                            'isOptional' => false,
                            'default' => null,
                        ],
                    ],
                ],
                'guards' => ['Kuick\Tests\Mocks\RequestDependentGuardMock']
            ],
        ], $routeMatcher->getRoutes());
    }

    public function testIfCachedContainerWorks(): void
    {
        putenv('KUICK_APP_ENV=prod');
        //first build - create cache
        $container = $this->getContainer();
        $routeMatcher = $container->get(Router::class);
        assertCount(2, $routeMatcher->getRoutes());
    }

    private function getContainer(): ContainerInterface
    {
        //cached from
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            'kuick.app.env' => 'prod',
            'kuick.app.project.dir' => self::$projectDir,
            LoggerInterface::class => new NullLogger(),
        ]);
        (new BuildRouter($builder))();
        return $builder->build();
    }
}
