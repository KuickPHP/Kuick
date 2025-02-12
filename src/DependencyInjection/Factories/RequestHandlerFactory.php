<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\DependencyInjection\Factories;

use DI\ContainerBuilder;
use Kuick\Http\Server\FallbackRequestHandlerInterface;
use Kuick\Http\Server\StackRequestHandler;
use Kuick\Routing\RoutingMiddleware;
use Kuick\Security\SecurityMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

/**
 * Request handler factory
 */
class RequestHandlerFactory
{
    public function build(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            RequestHandlerInterface::class => function (
                ContainerInterface $container,
                LoggerInterface $logger
            ): RequestHandlerInterface {
                // the default request handler is a Stack Request Handler (by Kuick)
                $requestHandler = (new StackRequestHandler($container->get(FallbackRequestHandlerInterface::class)))
                    ->addMiddleware($container->get(SecurityMiddleware::class))
                    ->addMiddleware($container->get(RoutingMiddleware::class));
                $logger->debug('Request handler initialized');
                return $requestHandler;
            }
        ]);
    }
}
