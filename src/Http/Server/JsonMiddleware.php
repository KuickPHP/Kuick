<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http\Server;

use Kuick\Http\Message\JsonErrorResponse;
use Kuick\Http\Message\Response;
use Monolog\Level;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Throwable;

class JsonMiddleware implements MiddlewareInterface
{
    private const EXCEPTION_CODE_LOG_LEVEL_MAP = [
        Response::HTTP_NOT_FOUND => Level::Notice,
        Response::HTTP_UNAUTHORIZED => Level::Notice,
        Response::HTTP_BAD_REQUEST => Level::Notice,
        Response::HTTP_METHOD_NOT_ALLOWED => Level::Notice,
        Response::HTTP_FORBIDDEN => Level::Notice,
        Response::HTTP_CONFLICT => Level::Notice,
        Response::HTTP_NOT_IMPLEMENTED => Level::Warning,
        Response::HTTP_GATEWAY_TIMEOUT => Level::Error,
        Response::HTTP_BAD_GATEWAY => Level::Error,
        Response::HTTP_INTERNAL_SERVER_ERROR => Level::Error,
    ];

    public function __construct(
        private LoggerInterface $logger
    )
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->logger->info('Handling JSON request: ' . $request->getMethod() . ' ' . $request->getUri()->getPath());
        try {
            return $handler->handle($request);
        } catch (Throwable $throwable) {
            $logLevel = self::EXCEPTION_CODE_LOG_LEVEL_MAP[$throwable->getCode()] ?? LogLevel::EMERGENCY;
            $this->logger->log(
                $logLevel,
                $throwable->getMessage() . ' ' . $throwable->getFile() . ' (' . $throwable->getLine() . ') ' . $throwable->getTraceAsString()
            );
            return new JsonErrorResponse($throwable->getMessage(), is_int($throwable->getCode()) ? $throwable->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
