<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use Kuick\Http\Server\Events\RequestReceived;
use Kuick\Http\Server\Events\ResponseCreated;
use Psr\Http\Message\ServerRequestInterface;

/**
 * HTTP Application Kernel
 */
class HttpKernel extends KernelAbstract
{
    public function handleRequest(ServerRequestInterface $request): void
    {
        $this->getEventDispatcher()->dispatch(new ResponseCreated(
            $this->getEventDispatcher()->dispatch(new RequestReceived($request))->getResponse()
        ));
    }
}
