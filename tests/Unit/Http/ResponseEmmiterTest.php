<?php

namespace Tests\Kuick\Http;

use Kuick\Http\JsonErrorResponse;
use Kuick\Http\JsonResponse;
use Kuick\Http\ResponseCodes;
use Kuick\Http\ResponseEmmiter;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

/**
 * @covers \Kuick\Http\ResponseEmmiter
 */
class ResponseEmmiterTest extends TestCase
{
    /**
     * Needs to be run in separate process, cause emmiter sends headers
     * @runInSeparateProcess
     */
    public function testEmmitedResponse(): void
    {
        $response = new JsonResponse(['test']);
        ob_start();
        (new ResponseEmmiter())($response);
        $content = ob_get_clean();
        assertEquals('["test"]', $content);
        assertEquals(['Content-Type: application/json'], xdebug_get_headers());
    }
}
