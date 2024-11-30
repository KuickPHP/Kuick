<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Router;

use Kuick\Http\ResponseCodes;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/**
 *
 */
class ActionLauncher
{
    public function __construct(private ContainerInterface $container, private LoggerInterface $logger)
    {
    }

    public function __invoke(array $route, ServerRequestInterface $request): ResponseInterface
    {
        if (empty($route)) {
            $this->logger->info('No action was executed to serve OPTIONS');
            return new Response(ResponseCodes::NO_CONTENT);
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
            $this->logger->info('Executing guard: ' . $guardName);
            call_user_func_array($this->container->get($guardName), $this->getArguments($guardName, $route, $request));
            $this->logger->info('Guard OK: ' . $guardName);
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
