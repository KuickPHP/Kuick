<?php

namespace Kuick\Tests\Unit\Routing;

use Kuick\Routing\Router;
use Kuick\Routing\ExecutableRoute;
use Kuick\Routing\MethodMismatchedException;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * @covers \Kuick\Routing\Router
 */
class RouterTest extends TestCase
{
    public function testAddingAndMatchingASingleRoute(): void
    {
        $controllerMock = function () {
        };
        $router = (new Router(new NullLogger()))
            ->addRoute('/test', $controllerMock, ['GET', 'POST']);

        $executableRoute = $router->matchRoute(new ServerRequest('GET', '/test'));
        $this->assertInstanceOf(ExecutableRoute::class, $executableRoute);
        $this->assertEquals(['GET', 'POST'], $executableRoute->methods);

        //not found
        $this->assertNull($router->matchRoute(new ServerRequest('GET', '/not-found')));
    }

    public function testAddingAndMatchingMultipleRoutes(): void
    {
        $controllerMock = function () {
        };
        $router = (new Router(new NullLogger()))
            ->addRoute('/sample', $controllerMock, ['GET'])
            ->addRoute('/sample', $controllerMock, ['POST'])
            ->addRoute('/test', $controllerMock, ['GET', 'POST']);

        $executableRoute = $router->matchRoute(new ServerRequest('POST', '/test'));
        $this->assertInstanceOf(ExecutableRoute::class, $executableRoute);
        $this->assertEquals(['GET', 'POST'], $executableRoute->methods);

        //method not allowed
        $this->expectException(MethodMismatchedException::class);
        $router->matchRoute(new ServerRequest('PUT', '/test'));
    }
}
