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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Throwable;

class JsonThrowableRequestHandler implements ThrowableRequestHandlerInterface
{
    private const EXCEPTION_CODE_LOG_LEVEL_MAP = [
        Response::HTTP_NOT_FOUND => LogLevel::NOTICE,
        Response::HTTP_UNAUTHORIZED => LogLevel::NOTICE,
        Response::HTTP_BAD_REQUEST => LogLevel::NOTICE,
        Response::HTTP_METHOD_NOT_ALLOWED => LogLevel::NOTICE,
        Response::HTTP_FORBIDDEN => LogLevel::NOTICE,
        Response::HTTP_CONFLICT => LogLevel::NOTICE,
        Response::HTTP_NOT_IMPLEMENTED => LogLevel::WARNING,
    ];

    private Throwable $throwable;

    public function __construct(
        private LoggerInterface $logger
    )
    {
    }

    public function setThrowable(Throwable $throwable): self
    {
        $this->throwable = $throwable;
        return $this;
    }

    public function getThrowable(): Throwable
    {
        return $this->throwable;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $logLevel = self::EXCEPTION_CODE_LOG_LEVEL_MAP[$this->throwable->getCode()] ?? LogLevel::ERROR;
        $this->logger->log(
            $logLevel, 
            $this->getResponseCode() >= Response::HTTP_INTERNAL_SERVER_ERROR ?
                $this->getExceptionDetailedInformation() :
                $this->getExceptionMessage()
        );
        return new JsonErrorResponse($this->throwable->getMessage(), $this->getResponseCode());
    }

    private function getExceptionMessage(): string
    {
        return $this->throwable->getMessage();
    }

    private function getExceptionDetailedInformation(): string
    {
        return $this->getExceptionMessage() . ' ' . $this->throwable->getFile() . ' (' . $this->throwable->getLine() . ') ' . $this->throwable->getTraceAsString();
    }

    private function getResponseCode(): int
    {
        if (!is_int($this->throwable->getCode())) {
            return Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        if ($this->throwable->getCode() < Response::HTTP_BAD_REQUEST || $this->throwable->getCode() > Response::HTTP_GATEWAY_TIMEOUT) {
            return Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        return $this->throwable->getCode();
    }
}