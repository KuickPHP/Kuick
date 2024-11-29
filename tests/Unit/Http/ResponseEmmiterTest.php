<?php

namespace Tests\Kuick\Http;

use Kuick\Http\JsonErrorResponse;
use Kuick\Http\JsonResponse;
use Kuick\Http\ResponseCodes;
use Kuick\Http\ResponseEmmiter;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\Http\ResponseEmmiter
 */
class ResponseEmmiterTest extends TestCase
{
    public function testEmmitedResponse(): void
    {
        $re = new ResponseEmmiter();
        $response = new JsonResponse(['test']);
        ob_start();
            $re($response);
        $content = ob_get_clean();
        assertEquals('["test"]', $content);
    }
}
