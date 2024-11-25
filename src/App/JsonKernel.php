<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use Kuick\App\Router\ActionLauncher;
use Kuick\App\Router\RouteMatcher;
use Kuick\Http\JsonErrorResponse;
use Kuick\Http\Request;
use Kuick\Http\Response;
use Monolog\Level;
use Throwable;

/**
 * JSON Application Kernel
 */
final class JsonKernel extends KernelAbstract
{
    private const EXCEPTION_CODE_LOG_LEVEL_MAP = [
        Response::HTTP_NOT_FOUND => Level::Notice,
        Response::HTTP_UNAUTHORIZED => Level::Notice,
        Response::HTTP_BAD_REQUEST => Level::Warning,
        Response::HTTP_METHOD_NOT_ALLOWED => Level::Warning,
        Response::HTTP_FORBIDDEN => Level::Warning,
    ];

    public function __invoke(Request $request): void
    {
        try {
            $this->logger->info('Handling JSON request: ' . $request->getPathInfo());
            //localization setup
            ($this->container->get(AppSetLocalization::class))();
            //matching and launching UI action
            $response = ($this->container->get(ActionLauncher::class))(
                $this->container->get(RouteMatcher::class)->findRoute($request),
                $request
            );
            $response->send();
        } catch (Throwable $error) {
            (new JsonErrorResponse($error->getMessage(), $error->getCode()))->send();
            $this->logger->log(
                self::EXCEPTION_CODE_LOG_LEVEL_MAP[$error->getCode()] ?? Response::HTTP_INTERNAL_SERVER_ERROR,
                $error->getMessage() . ' ' . $error->getFile() . ' (' . $error->getLine() . ') ' . $error->getTraceAsString()
            );
        }
    }
}
