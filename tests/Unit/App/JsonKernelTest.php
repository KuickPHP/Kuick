<?php

namespace Tests\Kuick\App;

use Kuick\App\JsonKernel;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\App\JsonKernel
 * @covers \Kuick\App\KernelAbstract
 */
class JsonKernelTest extends TestCase
{
    /**
     * Needs to be run in separate process, cause emmiter sends headers
     * @runInSeparateProcess
     */
    public function testIfNotFoundRouteEmmitsNotFoundResponse(): void
    {
        $jk = new JsonKernel();
        $request = new ServerRequest('GET', 'something');
        ob_start();
        $jk($request);
        $data = ob_get_clean();
        assertEquals('{"error":"Not found"}', $data);
    }
}
