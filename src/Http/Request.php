<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request extends SymfonyRequest
{
    public const ALL_METHODS = [
        self::METHOD_CONNECT,
        self::METHOD_DELETE,
        self::METHOD_GET,
        self::METHOD_HEAD,
        self::METHOD_OPTIONS,
        self::METHOD_PATCH,
        self::METHOD_POST,
        self::METHOD_PURGE,
        self::METHOD_PUT,
        self::METHOD_TRACE,
    ];
}
