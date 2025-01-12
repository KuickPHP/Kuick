<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http\Server\Events;

use Kuick\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;

final class RequestReceived
{
    private Response $response;

    public function __construct(private ServerRequestInterface $request)
    {
    }

    public function setResponse(Response $response): self
    {
        $this->response = $response;
        return $this;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}