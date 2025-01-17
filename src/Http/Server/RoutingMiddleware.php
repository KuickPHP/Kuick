<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http\Server;

use Kuick\App\InvokableArgumentReflector;
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
        // @TODO: consider adding those invokables to the router (? maybe)
        private ContainerInterface $container,
        // @TODO: remove this dependency, move it out to the Router configuration
        private InvokableArgumentReflector $invokableArgumentReflector,
        private LoggerInterface $logger,
    )
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeMatch = $this->router->matchRoute($request);
        // execute guards
        if (!empty($routeMatch->route->guards)) {
            $this->logger->debug('Executing guards for route: ' . $routeMatch->route->path);
            $this->executeGuards($routeMatch, $request);
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

    private function executeGuards(RouteMatch $routeMatch, ServerRequestInterface $request): void
    {
        foreach ($routeMatch->route->guards as $guardName) {
            $this->logger->debug('Executing guard: ' . $guardName);
            call_user_func_array(
                $this->container->get($guardName),
                $this->getArguments($guardName, $routeMatch, $request)
            );
            $this->logger->debug('Guard pass: ' . $guardName);
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