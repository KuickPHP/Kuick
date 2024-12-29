<?php

namespace Kuick\Tests\App\DIFactories\Utils;

use DI\ContainerBuilder;
use Kuick\App\DIFactories\BuildRouter;
use Kuick\App\DIFactories\Utils\RouteParser;
use Kuick\Http\Server\Router;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Filesystem\Filesystem;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\App\DIFactories\Utils\RouteParser
 */
class RouteParserTest extends TestCase
{
    public static string $projectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = dirname(__DIR__) . '/../../../Mocks/MockProjectDir';
        $cacheFile = self::$projectDir . '/var/cache/kuick-app-routes.php';
        (new Filesystem())->remove($cacheFile);
    }

    public function testIfRoutesAreParsedCorrectly(): void
    {
        $parser = new RouteParser(new NullLogger());
        assertEquals([
            [
                'path' => '/hello/(?<userId>[0-9]{1,12})',
                'controller' => 'Kuick\Tests\Mocks\ControllerMock',
                'arguments' => [
                    'Kuick\Tests\Mocks\ControllerMock' => [
                        'userId' => [
                            'type' => 'int',
                            'isOptional' => false,
                            'default' => null,
                        ],
                    ],
                ],
            ],
            [
                'method' => 'POST',
                'path' => '/',
                'controller' => 'Kuick\Tests\Mocks\RequestDependentControllerMock',
                'arguments' =>  [
                    'Kuick\Tests\Mocks\RequestDependentControllerMock' => [
                        'request' => [
                            'type' => 'Psr\Http\Message\ServerRequestInterface',
                            'isOptional' => false,
                            'default' => null,
                        ],
                    ],
                    'Kuick\Tests\Mocks\RequestDependentGuardMock' => [
                        'request' => [
                            'type' => 'Psr\Http\Message\ServerRequestInterface',
                            'isOptional' => false,
                            'default' => null,
                        ],
                    ],
                ],
                'guards' => ['Kuick\Tests\Mocks\RequestDependentGuardMock']
            ],
        ], $parser(self::$projectDir, 'dev'));
    }
    public function testIfRoutesAreCachedForProd(): void
    {
        $parser = new RouteParser(new NullLogger());
        assertEquals([
            [
                'path' => '/hello/(?<userId>[0-9]{1,12})',
                'controller' => 'Kuick\Tests\Mocks\ControllerMock',
                'arguments' => [
                    'Kuick\Tests\Mocks\ControllerMock' => [
                        'userId' => [
                            'type' => 'int',
                            'isOptional' => false,
                            'default' => null,
                        ],
                    ],
                ],
            ],
            [
                'method' => 'POST',
                'path' => '/',
                'controller' => 'Kuick\Tests\Mocks\RequestDependentControllerMock',
                'arguments' =>  [
                    'Kuick\Tests\Mocks\RequestDependentControllerMock' => [
                        'request' => [
                            'type' => 'Psr\Http\Message\ServerRequestInterface',
                            'isOptional' => false,
                            'default' => null,
                        ],
                    ],
                    'Kuick\Tests\Mocks\RequestDependentGuardMock' => [
                        'request' => [
                            'type' => 'Psr\Http\Message\ServerRequestInterface',
                            'isOptional' => false,
                            'default' => null,
                        ],
                    ],
                ],
                'guards' => ['Kuick\Tests\Mocks\RequestDependentGuardMock']
            ],
        ], $parser(self::$projectDir, 'prod'));
    }
}
