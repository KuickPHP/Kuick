<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Routing;

use Kuick\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Executable route
 */
class ExecutableRoute
{
    private array $params = [];

    public function __construct(
        public readonly string $path,
        public object $controller,
        public readonly array $methods = [RequestInterface::METHOD_GET],
    ) {
    }

    public function setParams(array $params = []): self
    {
        $this->params = $params;
        return $this;
    }

    public function execute(ServerRequestInterface $request): ResponseInterface
    {
        return $this->controller->__invoke($request->withQueryParams($this->params));
    }
}
