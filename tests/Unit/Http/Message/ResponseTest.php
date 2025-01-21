<?php

namespace Tests\Kuick\Unit\Http\Message;

use Kuick\Http\Message\Response;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

/**
 * @covers \Kuick\Http\Message\Response
 */
class ResponseTest extends TestCase
{
    public function testIfSimpleJsonResponseIsWellFormatted(): void
    {
        $response = new Response(200, [], 'test');
        assertEquals(200, $response->getStatusCode());
        assertEquals('test', $response->getBody()->getContents());
    }

    public function testMoreComplicatedJsonResponseIsWellFormatted(): void
    {
        $response = (new Response(Response::HTTP_OK, ['X-One-header' => 'one'], 'body'))
            ->withStatus(Response::HTTP_CREATED)
            ->withHeader('X-Header', 'header')
            ->withAddedHeader('X-test', 'remove')
            ->withAddedHeader('X-Another', 'another')
            ->withProtocolVersion('1.1')
            ->withoutHeader('X-test')
            ->withBody((new Psr17Factory())->createStream('{"Hello world"}'));

        assertEquals('one', $response->getHeaderLine('X-One-header'));
        assertEquals(['one'], $response->getHeader('X-One-header'));

        assertTrue($response->hasHeader('X-Header'));
        assertEquals('another', $response->getHeaderLine('X-Another'));

        assertFalse($response->hasHeader('X-inexistent'));
        assertFalse($response->hasHeader('X-test'));
        assertEquals('1.1', $response->getProtocolVersion());
        assertEquals('Created', $response->getReasonPhrase());

        assertEquals([
            'X-One-header',
            'X-Header',
            'X-Another'
        ], array_keys($response->getHeaders()));

        assertEquals(201, $response->getStatusCode());
        assertEquals('{"Hello world"}', $response->getBody()->getContents());
    }
}
