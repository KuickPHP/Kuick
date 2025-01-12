<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http\Server;

use Kuick\Http\Message\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class ActionHandler implements RequestHandlerInterface
{
    public function __construct(
        private ContainerInterface $container,
        private Router $router,
        private LoggerInterface $logger
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $routeMatch = $this->router->matchRoute($request);
        // empty route for OPTIONS
        if (null === $routeMatch) {
            $this->logger->info('No action was executed to serve OPTIONS');
            return new Response(Response::HTTP_NO_CONTENT);
        }
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
            $this->getArguments($routeMatch->route->controllerArguments, $routeMatch, $request)
        );
    }

    private function executeMiddlewares(RouteMatch $routeMatch, ServerRequestInterface $request): void
    {
        foreach ($routeMatch->route->middlewares as $middlewareName) {
            $this->logger->debug('Executing middleware: ' . $middlewareName);
            call_user_func_array(
                $this->container->get($middlewareName),
                $this->getArguments($routeMatch->route->middlewareArguments[$middlewareName] ?? [], $routeMatch, $request)
            );
            $this->logger->debug('Middleware completed: ' . $middlewareName);
        }
    }

    private function getArguments(array $methodArguments, RouteMatch $routeMatch, ServerRequestInterface $request): array
    {
        $arguments = [];
        foreach ($methodArguments as $argName => $argProperties) {
            if ($argProperties['type'] == ServerRequestInterface::class) {
                $arguments[$argName] = $request;
                continue;
            }
            $arguments[$argName] = $routeMatch->params[$argName] ?? $argProperties['default'];
        }
        return $arguments;
    }
}
