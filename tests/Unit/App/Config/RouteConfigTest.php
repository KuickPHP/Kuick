<?php

namespace Kuick\Tests\App\Config;

use Kuick\App\Config\RouteConfig;
use Kuick\EventDispatcher\RoutePriority;
use Kuick\Tests\Mocks\MockRoute;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kuick\App\Config\RouteConfig
 */
class RouteConfigTest extends TestCase
{
    public function testIfRouteConfigIsDefinedWithTheDefaultMethods(): void
    {
        $routeConfig = new RouteConfig('/test', MockRoute::class);
        $this->assertEquals('/test', $routeConfig->path);
        $this->assertEquals(MockRoute::class, $routeConfig->controllerClassName);
        $this->assertEquals(['GET'], $routeConfig->methods);
        $anotherConfig = new RouteConfig('/test', MockRoute::class, ['GET', 'PUT']);
        $this->assertEquals(['GET', 'PUT'], $anotherConfig->methods);
    }
}
