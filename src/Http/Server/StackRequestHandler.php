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
use Kuick\Http\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class StackRequestHandler implements RequestHandlerInterface
{
    private array $middlewares = [];

    public function __construct(
        private ThrowableRequestHandlerInterface $throwableRequestHandler,
    )
    {
    }

    public function addMiddleware(MiddlewareInterface $middleware): self
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            // Last middleware in the queue has called on the request handler.
            if (empty($this->middlewares)) {
                throw new NotFoundException('Not found');
            }
            $middleware = array_shift($this->middlewares);
            return $middleware->process($request, $this);
        } catch (Throwable $throwable) {
            return $this->throwableRequestHandler
                ->setThrowable($throwable)
                ->handle($request);
        }
    }
}