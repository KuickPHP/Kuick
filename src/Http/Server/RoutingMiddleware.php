<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http\Server;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class RoutingMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Router $router,
        private ContainerInterface $container,
        private InvokableArgumentReflector $invokableArgumentReflector,
        private LoggerInterface $logger,
    )
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeMatch = $this->router->matchRoute($request);
        // execute middlewares
        if (!empty($routeMatch->route->middlewares)) {
            $this->logger->debug('Executing middlewares for route: ' . $routeMatch->route->path);
            $this->executeMiddlewares($routeMatch, $request);
        }
        // execute action
        $response = $this->executeAction($routeMatch, $request);
        $this->logger->info('Action executed: ' . $routeMatch->route->controller);
        return $response;
    }

    private function executeAction(RouteMatch $routeMatch, ServerRequestInterface $request): ResponseInterface
    {
        return call_user_func_array(
            $this->container->get($routeMatch->route->controller),
            $this->getArguments($routeMatch->route->controller, $routeMatch, $request)
        );
    }

    private function executeMiddlewares(RouteMatch $routeMatch, ServerRequestInterface $request): void
    {
        foreach ($routeMatch->route->middlewares as $middlewareName) {
            $this->logger->debug('Executing middleware: ' . $middlewareName);
            call_user_func_array(
                $this->container->get($middlewareName),
                $this->getArguments($middlewareName, $routeMatch, $request)
            );
            $this->logger->debug('Middleware completed: ' . $middlewareName);
        }
    }

    private function getArguments(string $className, RouteMatch $routeMatch, ServerRequestInterface $request): array
    {
        $invokeArguments = $this->invokableArgumentReflector->getForClass($className);
        $arguments = [];
        foreach ($invokeArguments as $argName => $argProperties) {
            if ($argProperties['type'] == ServerRequestInterface::class) {
                $arguments[$argName] = $request;
                continue;
            }
            $arguments[$argName] = $routeMatch->params[$argName] ?? $argProperties['default'];
        }
        return $arguments;
    }
}