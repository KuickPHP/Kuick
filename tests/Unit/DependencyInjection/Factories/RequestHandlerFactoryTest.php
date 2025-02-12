<?php

namespace Tests\Unit\App\DependencyInjection\Factories;

use DI\ContainerBuilder;
use Kuick\Framework\DependencyInjection\Factories\RequestHandlerFactory;
use Kuick\Http\Server\FallbackRequestHandlerInterface;
use Kuick\Http\Server\JsonNotFoundRequestHandler;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @covers Kuick\Framework\DependencyInjection\Factories\RequestHandlerFactory
 */
class RequestHandlerFactoryTest extends TestCase
{
    public function testIfRequestHandlerIsBuilt(): void
    {
        $builder = new ContainerBuilder();
        $builder->useAttributes(true);
        $builder->addDefinitions([
            LoggerInterface::class => new NullLogger(),
            FallbackRequestHandlerInterface::class => new JsonNotFoundRequestHandler(),
        ]);
        (new RequestHandlerFactory())->build($builder);
        $container = $builder->build();
        $this->assertInstanceOf(RequestHandlerInterface::class, $container->get(RequestHandlerInterface::class));
    }
}
