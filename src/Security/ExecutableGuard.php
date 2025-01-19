<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Security;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Executable guard
 */
class ExecutableGuard
{
    public const METHOD_GET = 'GET';
    public const METHOD_HEAD = 'HEAD';
    public const METHOD_OPTIONS = 'OPTIONS';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_DELETE = 'DELETE';

    private const REQUEST_PARAMETER_NAME = 'request';

    public function __construct(
        public readonly string $path,
        public object $guard,
        public readonly array $methods = [self::METHOD_GET],
    ) {
    }

    public function execute(ServerRequestInterface $request): void
    {
        call_user_func_array($this->guard, [self::REQUEST_PARAMETER_NAME => $request]);
    }
}
