<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Router;

use Kuick\Http\JsonResponse;
use Kuick\Http\Request;
use Kuick\Http\Response;
use Kuick\UI\ActionInterface;
use Kuick\Security\GuardInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 *
 */
class ActionLauncher
{
    public function __construct(private ContainerInterface $container, private LoggerInterface $logger)
    {
    }

    public function __invoke(array $route, Request $request): Response|JsonResponse
    {
        if (empty($route)) {
            $this->logger->info('No action was executed to serve OPTIONS');
            return (new Response())->setStatusCode(Response::HTTP_NO_CONTENT);
        }
        if (isset($route['guards'])) {
            $this->logger->debug('Executing guards');
            $this->executeGuards($route['guards'], $request);
        }
        $action = $this->container->get($route['action']);
        if (!($action instanceof ActionInterface)) {
            throw new RouterException($route['action'] . ' is not an Action');
        }
        $response = $action->__invoke($request);
        $this->logger->info('Action executed: ' . $route['action']);
        return $response;
    }

    private function executeGuards(array $guards, Request $request): void
    {
        foreach ($guards as $guardName) {
            $guard = $this->container->get($guardName);
            if (!($guard instanceof GuardInterface)) {
                throw new RouterException($guardName . ' is not a Guard');
            }
            $this->logger->info('Guard executed: ' . $guardName);
            $guard->__invoke($request);
        }
    }
}
