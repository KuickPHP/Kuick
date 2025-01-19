<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http\Server;

use Psr\Http\Server\RequestHandlerInterface;
use Exception;

/**
 * Exception Request Handler Interface
 */
interface ExceptionRequestHandlerInterface extends RequestHandlerInterface
{
    public function setException(Exception $exception): self;
    public function getException(): Exception;
}