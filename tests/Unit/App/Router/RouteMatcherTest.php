<?php

namespace Tests\Kuick\App\Router;

use Kuick\App\Router\RouteMatcher;
use Kuick\Http\MethodNotAllowedException;
use Kuick\Http\NotFoundException;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Tests\Kuick\Mocks\ControllerMock;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\App\Router\RouteMatcher
 */
class RouteMatcherTest extends TestCase
{
    private const ROUTES = [
        [
            'path' => '/',
            'controller' => ControllerMock::class,
        ],
        [
            'method' => 'PUT',
            'path' => '/api/user/(?<userId>[0-9]{1,8})',
            'controller' => ControllerMock::class,
        ],
        [
            'method' => 'GET',
            'path' => '/ping/(?<message>[a-zA-Z0-9-]+)',
            'controller' => ControllerMock::class,
        ],
    ];

    public function testIfRoutesCanBeSetAndGet(): void
    {
        $rm = new RouteMatcher(new NullLogger());
        $rm->setRoutes(self::ROUTES);
        assertEquals(self::ROUTES, $rm->getRoutes());
    }

    public function testIfOptionsGetsDefaultOptionsController(): void
    {
        $rm = new RouteMatcher(new NullLogger());
        $rm->setRoutes(self::ROUTES);

        assertEquals([], $rm->findRoute(new ServerRequest('OPTIONS', '/whatever')));
    }

    public function testIfOptionsReturnsEmptyRoute(): void
    {
        $rm = new RouteMatcher(new NullLogger());
        $rm->setRoutes(self::ROUTES);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage('Not found');
        $rm->findRoute(new ServerRequest('GET', '/inexistent'));
    }

    public function testIfRouterMatchesDefinedRoutes(): void
    {
        $rm = new RouteMatcher(new NullLogger());
        $rm->setRoutes(self::ROUTES);

        assertEquals(
            self::ROUTES[0] + ['params' => []],
            $rm->findRoute(new ServerRequest('GET', '/'))
        );

        assertEquals(
            self::ROUTES[1] + ['params' => ['userId' => 539]],
            $rm->findRoute(new ServerRequest('PUT', '/api/user/539'))
        );

        assertEquals(
            self::ROUTES[2] + ['params' => ['message' => 'Some-Message-492']],
            $rm->findRoute(new ServerRequest('GET', '/ping/Some-Message-492'))
        );
    }

    public function testIfMethodMismatchGivesThrowsMethodNotAllowed(): void
    {
        $rm = new RouteMatcher(new NullLogger());
        $rm->setRoutes(self::ROUTES);

        $this->expectException(MethodNotAllowedException::class);
        $this->expectExceptionCode(405);
        $this->expectExceptionMessage('POST method is not allowed for path: /');
        $rm->findRoute(new ServerRequest('POST', '/'));
    }
}
