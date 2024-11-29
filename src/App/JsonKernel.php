<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use Kuick\App\Router\ActionLauncher;
use Kuick\App\Router\RouteMatcher;
use Kuick\Http\JsonErrorResponse;
use Kuick\Http\ResponseCodes;
use Kuick\Http\ResponseEmmiter;
use Monolog\Level;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LogLevel;
use Throwable;

/**
 * JSON Application Kernel
 */
final class JsonKernel extends KernelAbstract
{
    private const EXCEPTION_CODE_LOG_LEVEL_MAP = [
        ResponseCodes::NOT_FOUND => Level::Notice,
        ResponseCodes::UNAUTHORIZED => Level::Notice,
        ResponseCodes::BAD_REQUEST => Level::Warning,
        ResponseCodes::METHOD_NOT_ALLOWED => Level::Warning,
        ResponseCodes::FORBIDDEN => Level::Warning,
    ];

    public function __invoke(ServerRequestInterface $request): void
    {
        try {
            $this->logger->info('Handling JSON request: ' . $request->getUri()->getPath());
            //emmit response
            (new ResponseEmmiter)(($this->container->get(ActionLauncher::class))(
                $this->container->get(RouteMatcher::class)->findRoute($request),
                $request
            ));
        } catch (Throwable $error) {
            //emmit error response
            (new ResponseEmmiter)(new JsonErrorResponse($error->getMessage(), $error->getCode()));
            $logLevel = self::EXCEPTION_CODE_LOG_LEVEL_MAP[$error->getCode()] ?? LogLevel::EMERGENCY;
            $this->logger->log(
                $logLevel,
                $error->getMessage() . ' ' . $error->getFile() . ' (' . $error->getLine() . ') ' . $error->getTraceAsString()
            );
        }
    }
}
