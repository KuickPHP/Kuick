<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\DependencyInjection;

use DI\ContainerBuilder;
use Kuick\App\Kernel;
use Kuick\App\SystemCacheInterface;
use Kuick\Http\Server\ExceptionRequestHandlerInterface;
use Kuick\Http\Server\RequestHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

/**
 *
 */
class RequestHandlerBuilder
{
    public function __construct(private ContainerBuilder $builder)
    {
    }

    public function __invoke(): void
    {
        // default request handler is a Stack Request Handler (by Kuick)
        $this->builder->addDefinitions([RequestHandlerInterface::class => function (ContainerInterface $container, LoggerInterface $logger, SystemCacheInterface $cache): RequestHandlerInterface {
            $requestHandler = new RequestHandler($container->get(ExceptionRequestHandlerInterface::class));
            foreach ((new MiddlewareConfigLoader($cache, $logger))($container->get(Kernel::DI_PROJECT_DIR_KEY)) as $middlewareClassName) {
                $requestHandler->addMiddleware($container->get($middlewareClassName));
            }
            return $requestHandler;
        }]);
    }
}
