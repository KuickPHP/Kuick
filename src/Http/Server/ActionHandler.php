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
    )
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $route = $this->router->findRoute($request);
        if (empty($route)) {
            $this->logger->info('No action was executed to serve OPTIONS');
            return new Response(Response::HTTP_NO_CONTENT);
        }
        if (isset($route['guards'])) {
            $this->logger->debug('Executing guards');
            $this->executeGuards($route, $request);
        }
        //run action
        $response = call_user_func_array($this->container->get($route['controller']), $this->getArguments($route['controller'], $route, $request));
        $this->logger->info('Action executed: ' . $route['controller']);
        return $response;
    }

    private function executeGuards(array $route, ServerRequestInterface $request): void
    {
        foreach ($route['guards'] as $guardName) {
            $this->logger->debug('Executing guard: ' . $guardName);
            call_user_func_array($this->container->get($guardName), $this->getArguments($guardName, $route, $request));
            $this->logger->debug('Guard OK: ' . $guardName);
        }
    }

    private function getArguments(string $targetClass, array $route, ServerRequestInterface $request): array
    {
        $arguments = [];
        foreach ($route['arguments'][$targetClass] as $argName => $argProperties) {
            if ($argProperties['type'] == ServerRequestInterface::class) {
                $arguments[$argName] = $request;
                continue;
            }
            $arguments[$argName] = $route['params'][$argName] ?? $argProperties['default'];
        }
        return $arguments;
    }
}