<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http\Server;

use Kuick\Http\Message\JsonResponse;
use Kuick\Http\Message\Response;
use Kuick\Http\Server\ExceptionRequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Exception;

/**
 * Exception Handler (rendering JSON)
 */
class ExceptionJsonRequestHandler implements ExceptionRequestHandlerInterface
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

    private Exception $Exception;

    public function __construct(
        private LoggerInterface $logger
    )
    {
    }

    public function setException(Exception $Exception): self
    {
        $this->Exception = $Exception;
        return $this;
    }

    public function getException(): Exception
    {
        return $this->Exception;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $logLevel = self::EXCEPTION_CODE_LOG_LEVEL_MAP[$this->Exception->getCode()] ?? LogLevel::ERROR;
        $this->logger->log(
            $logLevel, 
            $this->getResponseCode() >= Response::HTTP_INTERNAL_SERVER_ERROR ?
                $this->getExceptionDetailedInformation() :
                $this->getExceptionMessage()
        );
        return new JsonResponse(['error' => $this->Exception->getMessage()], $this->getResponseCode());
    }

    private function getExceptionMessage(): string
    {
        return $this->Exception->getMessage();
    }

    private function getExceptionDetailedInformation(): string
    {
        return $this->getExceptionMessage() . ' ' . $this->Exception->getFile() . ' (' . $this->Exception->getLine() . ') ' . $this->Exception->getTraceAsString();
    }

    private function getResponseCode(): int
    {
        if (!is_int($this->Exception->getCode())) {
            return Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        if ($this->Exception->getCode() < Response::HTTP_BAD_REQUEST || $this->Exception->getCode() > Response::HTTP_GATEWAY_TIMEOUT) {
            return Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        return $this->Exception->getCode();
    }
}