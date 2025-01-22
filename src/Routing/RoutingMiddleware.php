<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Routing;

use Kuick\Http\MethodNotAllowedException;
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
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $executableRoute = $this->router->matchRoute($request);
        } catch (MethodMismatchedException $exception) {
            throw new MethodNotAllowedException($exception->getMessage());
        }
        //route not found, continue to the RequestHandler
        if (null === $executableRoute) {
            return $handler->handle($request);
        }
        //executing action
        $response = $executableRoute->execute($request);
        $this->logger->info('Action executed: ' . get_class($executableRoute->controller));
        return $response;
    }
}
