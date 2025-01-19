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

    private array $params;

    public function __construct(
        public readonly string $path,
        public object $guard,
        public readonly array $methods = [
            self::METHOD_GET,
            self::METHOD_OPTIONS,
            self::METHOD_POST,
            self::METHOD_PUT,
            self::METHOD_PATCH,
            self::METHOD_DELETE,
        ],
    ) {
    }

    public function addParams(array $params = []): self
    {
        $this->params = $params;
        return $this;
    }

    public function execute(ServerRequestInterface $request): void
    {
        // adding guard parameters to the request query params
        call_user_func_array(
            $this->guard, 
            [self::REQUEST_PARAMETER_NAME => $request->withQueryParams($this->params)]
        );
    }
}
