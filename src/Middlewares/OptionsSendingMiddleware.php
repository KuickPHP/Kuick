<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Framework\Middlewares;

use Kuick\Http\Message\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Options Sending Middleware
 */
final class OptionsSendingMiddleware implements MiddlewareInterface
{
    private const OPTIONS_METHOD = 'OPTIONS';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 204 for OPTIONS
        if (self::OPTIONS_METHOD == $request->getMethod()) {
            return new Response(Response::HTTP_NO_CONTENT);
        }
        // forward if request method is different than OPTIONS
        return $handler->handle($request);
    }
}
