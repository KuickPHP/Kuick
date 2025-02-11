<?php

namespace Tests\Unit\App\DependencyInjection;

use DI\ContainerBuilder;
use Kuick\Framework\Config\ConfigException;
use Kuick\Framework\DependencyInjection\RequestHandlerBuilder;
use Kuick\Framework\SystemCache;
use Kuick\Framework\SystemCacheInterface;
use Kuick\Http\Server\ExceptionHtmlRequestHandler;
use Kuick\Http\Server\FallbackRequestHandlerInterface;
use Kuick\Http\Server\JsonNotFoundRequestHandler;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @covers Kuick\Framework\DependencyInjection\RequestHandlerBuilder
 */
class RequestHandlerBuilderTest extends TestCase
{
    public function testIfRequestHandlerIsBuilt(): void
    {
        $builder = new ContainerBuilder();
        $builder->useAttributes(true);
        $builder->addDefinitions([
            LoggerInterface::class => new NullLogger(),
            FallbackRequestHandlerInterface::class => new JsonNotFoundRequestHandler(),
        ]);
        (new RequestHandlerBuilder($builder))();
        $container = $builder->build();
        $this->assertInstanceOf(RequestHandlerInterface::class, $container->get(RequestHandlerInterface::class));
    }
}
