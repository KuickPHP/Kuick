<?php

namespace Tests\Kuick\Unit\Http\Message;

use Kuick\Http\Message\JsonResponse;
use Kuick\Http\Message\Response;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kuick\Http\Message\JsonResponse
 */
class JsonResponseTest extends TestCase
{
    public function testIfSimpleJsonResponseIsWellFormatted(): void
    {
        $response = new JsonResponse(['test' => 'example']);
        $this->assertEquals('application/json', $response->getHeaderLine('Content-type'));
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"test":"example"}', $response->getBody()->getContents());
    }

    public function testMoreComplicatedJsonResponseIsWellFormatted(): void
    {
        $response = (new JsonResponse(['test' => 'example'], Response::HTTP_OK, ['X-One-header' => 'one']))
            ->withStatus(Response::HTTP_CREATED)
            ->withHeader('X-Header', 'header')
            ->withAddedHeader('X-test', 'remove')
            ->withAddedHeader('X-Another', 'another')
            ->withProtocolVersion('1.1')
            ->withoutHeader('X-test')
            ->withBody((new Psr17Factory())->createStream('{"Hello world"}'));

        $this->assertEquals('application/json', $response->getHeaderLine('Content-type'));

        $this->assertEquals('one', $response->getHeaderLine('X-One-header'));
        $this->assertEquals(['one'], $response->getHeader('X-One-header'));

        $this->assertTrue($response->hasHeader('X-Header'));
        $this->assertEquals('another', $response->getHeaderLine('X-Another'));

        $this->assertFalse($response->hasHeader('X-inexistent'));
        $this->assertFalse($response->hasHeader('X-test'));
        $this->assertEquals('1.1', $response->getProtocolVersion());
        $this->assertEquals('Created', $response->getReasonPhrase());

        $this->assertEquals([
            'X-One-header',
            'Content-Type',
            'X-Header',
            'X-Another'
        ], array_keys($response->getHeaders()));

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('{"Hello world"}', $response->getBody()->getContents());
    }
}
