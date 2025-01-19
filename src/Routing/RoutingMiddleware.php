<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Routing;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class RoutingMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Router $router,
        private LoggerInterface $logger,
    )
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $executableRoute = $this->router->matchRoute($request);
        // execute action
        $controllerClass = get_class($executableRoute->controller);
        $response = $executableRoute->execute($request);
        $this->logger->info('Action executed: ' . $controllerClass);
        return $response;
    }
}