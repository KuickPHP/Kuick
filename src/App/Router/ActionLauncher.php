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
use Kuick\Security\GuardInterface;
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
            $this->executeGuards($route['guards'], $request);
        }
        //calculate prams
        $params = [];
        foreach ($route['invokeParams'] as $invokeParamName => $invokeParamProperties) {
            if ($invokeParamProperties['type'] == ServerRequestInterface::class) {
                $params[$invokeParamName] = $request;
                continue;
            }
            $params[$invokeParamName] = $route['params'][$invokeParamName] ?? $invokeParamProperties['default'];
        }
        //run action
        $response = call_user_func_array($this->container->get($route['controller']), $params);
        $this->logger->info('Action executed: ' . $route['controller']);
        return $response;
    }

    private function executeGuards(array $guards, ServerRequestInterface $request): void
    {
        foreach ($guards as $guardName) {
            $this->logger->info('Executing guard: ' . $guardName);
            $this->container->get($guardName)->__invoke($request);
            $this->logger->info('Guard OK: ' . $guardName);
        }
    }
}
