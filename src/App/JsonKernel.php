<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use Kuick\Http\ResponseEmitter;
use Kuick\Http\Server\ActionHandler;
use Kuick\Http\Server\JsonMiddleware;
use Psr\Http\Message\ServerRequestInterface;

/**
 * JSON Application Kernel
 */
final class JsonKernel extends KernelAbstract
{
    public function __invoke(ServerRequestInterface $request): void
    {
        (new ResponseEmitter())($this->container->get(JsonMiddleware::class)
            ->process($request, $this->container->get(ActionHandler::class)));
    }
}
