<?php

namespace Tests\Kuick\App;

use DI\Container;
use Kuick\App\AppException;
use Kuick\App\JsonKernel;
use Kuick\App\Router\ActionLauncher;
use Kuick\App\Router\RouteMatcher;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Tests\Kuick\Mocks\ContainerMock;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\App\JsonKernel
 * @covers \Kuick\App\KernelAbstract
 */
class JsonKernelTest extends TestCase
{
    public function test(): void
    {
        $container = new ContainerMock([
            RouteMatcher::class => new RouteMatcher(new NullLogger),
        ]);
        $jk = new JsonKernel($container);
        $request = new ServerRequest('GET', 'something');
        ob_start();
        $jk($request);
        $data = ob_get_clean();
        assertEquals('{"error":"Not found"}', $data);
    }
}
