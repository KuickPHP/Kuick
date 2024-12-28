<?php

namespace Kuick\Tests\Http\Message;

use Kuick\Http\Message\JsonErrorResponse;
use Kuick\Http\Message\Response;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\Http\Message\JsonErrorResponse
 */
class JsonErrorResponseTest extends TestCase
{
    public function testIfSimpleJsonResponseIsWellFormatted(): void
    {
        $response = new JsonErrorResponse('something went wrong', Response::HTTP_BAD_GATEWAY);
        assertEquals('application/json', $response->getHeaderLine('Content-type'));
        assertEquals(502, $response->getStatusCode());
        assertEquals('{"error":"something went wrong"}', $response->getBody()->getContents());
    }
}
