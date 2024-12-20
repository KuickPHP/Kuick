<?php

namespace Tests\Kuick\App;

use Kuick\App\JsonKernel;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\App\JsonKernel
 * @covers \Kuick\App\KernelAbstract
 */
class JsonKernelTest extends TestCase
{
    private static string $projectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(dirname(__DIR__) . '/../Mocks/MockProjectDir');
        $fs = new Filesystem();
        $fs->remove(self::$projectDir . '/var/cache');
    }

    /**
     * Needs to be run in separate process, cause emmiter sends headers
     * @runInSeparateProcess
     */
    public function testIfNotFoundRouteEmmitsNotFoundResponse(): void
    {
        $jk = new JsonKernel(self::$projectDir);
        $request = new ServerRequest('GET', 'something');
        ob_start();
        $jk($request);
        $data = ob_get_clean();
        assertEquals('{"error":"Not found"}', $data);
    }

    /**
     * Needs to be run in separate process, cause DI builder won't work other way
     * @runInSeparateProcess
     */
    public function testIfContainerReturnsBuiltContainer(): void
    {
        ob_start();
        $jk = new JsonKernel(self::$projectDir);
        ob_end_clean();
        $container = $jk->getContainer();
        assertEquals('Testing App', $container->get('kuick.app.name'));
    }
}
