<?php

namespace Tests\Kuick\Http;

use Kuick\Http\JsonErrorResponse;
use Kuick\Http\ResponseCodes;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\Http\JsonErrorResponse
 */
class JsonErrorResponseTest extends TestCase
{
    public function testIfSimpleJsonResponseIsWellFormatted(): void
    {
        $response = new JsonErrorResponse('something went wrong', ResponseCodes::BAD_GATEWAY);
        assertEquals('application/json', $response->getHeaderLine('Content-type'));
        assertEquals(502, $response->getStatusCode());
        assertEquals('{"error":"something went wrong"}', $response->getBody()->getContents());
    }
}
