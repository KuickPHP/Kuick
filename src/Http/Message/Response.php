<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http\Message;

use Nyholm\Psr7\Response as NyholmResponse;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseInterface
{
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;
    public const HTTP_ACCEPTED = 202;
    public const HTTP_NO_CONTENT = 204;
    public const HTTP_MOVED_PERMANENTLY = 301;
    public const HTTP_FOUND = 302;
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_METHOD_NOT_ALLOWED = 405;
    public const HTTP_CONFLICT = 409;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;
    public const HTTP_NOT_IMPLEMENTED = 501;
    public const HTTP_BAD_GATEWAY = 502;
    public const HTTP_SERVICE_UNAVAILABLE = 503;
    public const HTTP_GATEWAY_TIMEOUT = 504;

    private NyholmResponse $wrappedResponse;

    public function __construct(int $status = 200, array $headers = [], $body = null, string $version = '1.1', ?string $reason = null)
    {
        $this->wrappedResponse = new NyholmResponse($status, $headers, $body, $version, $reason);
    }

    public function getBody(): StreamInterface
    {
        return $this->wrappedResponse->getBody();
    }

    public function getHeader(string $name): array
    {
        return $this->wrappedResponse->getHeader($name);
    }

    public function getHeaderLine(string $name): string
    {
        return $this->wrappedResponse->getHeaderLine($name);
    }

    public function getHeaders(): array
    {
        return $this->wrappedResponse->getHeaders();
    }

    public function getProtocolVersion(): string
    {
        return $this->wrappedResponse->getProtocolVersion();
    }

    public function getReasonPhrase(): string
    {
        return $this->wrappedResponse->getReasonPhrase();
    }

    public function getStatusCode(): int
    {
        return $this->wrappedResponse->getStatusCode();
    }

    public function hasHeader(string $name): bool
    {
        return $this->wrappedResponse->hasHeader($name);
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        $this->wrappedResponse = $this->wrappedResponse->withAddedHeader($name, $value);
        return $this;
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        $this->wrappedResponse = $this->wrappedResponse->withBody($body);
        return $this;
    }

    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        $this->wrappedResponse = $this->wrappedResponse->withStatus($code, $reasonPhrase);
        return $this;
    }

    public function withHeader(string $name, $value): MessageInterface
    {
        $this->wrappedResponse = $this->wrappedResponse->withHeader($name, $value);
        return $this;
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        $this->wrappedResponse = $this->wrappedResponse->withProtocolVersion($version);
        return $this;
    }

    public function withoutHeader(string $name): MessageInterface
    {
        $this->wrappedResponse = $this->wrappedResponse->withoutHeader($name);
        return $this;
    }
}
