<?php

namespace Kuick\Tests\App;

use Kuick\App\HttpKernel;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\App\HttpKernel
 * @covers \Kuick\App\KernelAbstract
 */
class HttpKernelTest extends TestCase
{
    private static string $projectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(dirname(__DIR__) . '/../Mocks/MockProjectDir');
        (new Filesystem())->remove(self::$projectDir . '/var/cache');
    }

    /**
     * Needs to be run in separate process, cause emmiter sends headers
     * @runInSeparateProcess
     */
    public function testIfNotFoundRouteEmmitsNotFoundResponse(): void
    {
        $kernel = new HttpKernel(self::$projectDir);
        $request = new ServerRequest('GET', 'something');
        ob_start();
        $kernel($request);
        $data = ob_get_clean();
        assertEquals('{"error":"Not found"}', $data);
    }

    // /**
    //  * Needs to be run in separate process, cause DI builder won't work other way
    //  * @runInSeparateProcess
    //  */
    // public function testIfContainerReturnsBuiltContainer(): void
    // {
    //     ob_start();
    //     $kernel = new JsonKernel(self::$projectDir);
    //     ob_end_clean();
    //     $container = $kernel->getContainer();
    //     assertEquals('Testing App', $container->get('kuick.app.name'));
    // }
}
