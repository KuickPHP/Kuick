<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Security;

use Kuick\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Executable guard
 */
class ExecutableGuard
{
    private const REQUEST_PARAMETER_NAME = 'request';

    private array $params;

    public function __construct(
        public readonly string $path,
        public object $guard,
        public readonly array $methods = [
            RequestInterface::METHOD_GET,
            RequestInterface::METHOD_OPTIONS,
            RequestInterface::METHOD_POST,
            RequestInterface::METHOD_PUT,
            RequestInterface::METHOD_PATCH,
            RequestInterface::METHOD_DELETE,
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
