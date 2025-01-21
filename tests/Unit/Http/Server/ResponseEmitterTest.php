<?php

namespace Tests\Kuick\Unit\Http;

use Kuick\Http\Message\JsonResponse;
use Kuick\Http\Server\ResponseEmitter;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\Http\Server\ResponseEmitter
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
        (new ResponseEmitter())($response);
        $content = ob_get_clean();
        assertEquals('["test"]', $content);
        assertEquals(['Content-Type: application/json'], xdebug_get_headers());
    }
}
