<?php

namespace Tests\Unit\App\DependencyInjection;

use DI\ContainerBuilder;
use Kuick\App\DependencyInjection\RequestHandlerBuilder;
use Kuick\App\SystemCache;
use Kuick\App\SystemCacheInterface;
use Kuick\Http\Server\ExceptionHtmlRequestHandler;
use Kuick\Http\Server\ExceptionRequestHandlerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @covers \Kuick\App\DependencyInjection\RequestHandlerBuilder
 */
class RequestHandlerBuilderTest extends TestCase
{
    private static string $projectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(dirname(__DIR__) . '/../../Mocks/project-dir');
    }

    public function testBuildingRequestHandler(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            SystemCacheInterface::class => new SystemCache(self::$projectDir, 'dev'),
            LoggerInterface::class => new NullLogger(),
            ExceptionRequestHandlerInterface::class => new ExceptionHtmlRequestHandler(new NullLogger()),
            'kuick.app.projectDir' => self::$projectDir,
        ]);
        (new RequestHandlerBuilder($builder))();
        $container = $builder->build();
        $this->assertInstanceOf(RequestHandlerInterface::class, $container->get(RequestHandlerInterface::class));
    }
}
