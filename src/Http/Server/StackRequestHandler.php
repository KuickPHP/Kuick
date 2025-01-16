<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http\Server;

use DI\Attribute\Inject;
use Kuick\Http\Message\Response;
use Kuick\Http\NotFoundException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class StackRequestHandler implements RequestHandlerInterface
{
    public function __construct(
        private ThrowableRequestHandlerInterface $throwableRequestHandler,
        private ContainerInterface $container,
        #[Inject('kuick.app.middlewares')] private array $middlewares = [],
    )
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        //204 for OPTIONS
        if (Route::METHOD_OPTIONS == $request->getMethod()) {
            return new Response(Response::HTTP_NO_CONTENT);
        }
        try {
            // Last middleware in the queue has called on the request handler.
            if (empty($this->middlewares)) {
                throw new NotFoundException('Not found');
            }
            $middleware = array_shift($this->middlewares);
            $middlewareObject = $this->container->get($middleware->middleware);
            return $middlewareObject->process($request, $this);
        } catch (Throwable $throwable) {
            return $this->throwableRequestHandler
                ->setThrowable($throwable)
                ->handle($request);
        }
    }
}