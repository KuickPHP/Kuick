<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http;

use Nyholm\Psr7\Response;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class JsonResponse implements ResponseInterface
{
    private Response $wrappedResponse;
    private const DEFAULT_HEADER = ['Content-Type' => 'application/json'];

    public function __construct(array $body, int $code = ResponseCodes::OK, array $headers = [])
    {
        $this->wrappedResponse = new Response($code, array_merge($headers, self::DEFAULT_HEADER), json_encode($body));
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
