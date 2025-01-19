<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\Http\Server\ExceptionJsonRequestHandler;
use Kuick\Http\Server\ExceptionRequestHandlerInterface;

use function DI\autowire;

return [
    // The default Kuick Request Handler needs an Exception handler
    ExceptionRequestHandlerInterface::class => autowire(ExceptionJsonRequestHandler::class),
];