<?php

namespace Tests\Unit\App\DependencyInjection;

use DI\ContainerBuilder;
use Kuick\Framework\Config\ConfigException;
use Kuick\Framework\DependencyInjection\RouterBuilder;
use Kuick\Framework\SystemCache;
use Kuick\Framework\SystemCacheInterface;
use Kuick\Routing\ExecutableRoute;
use Kuick\Routing\Router;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @covers Kuick\Framework\DependencyInjection\RouterBuilder
 */
class RouterBuilderTest extends TestCase
{
    private static string $projectDir;
    private static string $invalidProjectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(dirname(__DIR__) . '/Mocks/project-dir');
        self::$invalidProjectDir = realpath(dirname(__DIR__) . '/Mocks/invalid-project-dir');
    }

    public function testIfRouterIsBuilt(): void
    {
        $builder = new ContainerBuilder();
        $builder->useAttributes(true);
        $builder->addDefinitions([
            SystemCacheInterface::class => new SystemCache(self::$projectDir, 'dev'),
            LoggerInterface::class => new NullLogger(),
            'app.projectDir' => self::$projectDir,
        ]);
        (new RouterBuilder($builder))();
        $container = $builder->build();
        $router = $container->get(Router::class);
        $this->assertInstanceOf(Router::class, $router);
        $this->assertInstanceOf(ExecutableRoute::class, $router->matchRoute(new ServerRequest('POST', '/')));
        $this->assertNull($router->matchRoute(new ServerRequest('GET', '/inexistent')));
    }

    public function testIfRouterRaisesExceptionForBrokenRoutes(): void
    {
        $builder = new ContainerBuilder();
        $builder->useAttributes(true);
        $builder->addDefinitions([
            SystemCacheInterface::class => new SystemCache(self::$invalidProjectDir, 'dev'),
            LoggerInterface::class => new NullLogger(),
            'app.projectDir' => self::$invalidProjectDir,
        ]);
        (new RouterBuilder($builder))();
        $this->expectException(ConfigException::class);
        $container = $builder->build();
        $container->get(Router::class);
    }
}
