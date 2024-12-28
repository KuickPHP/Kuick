<?php

namespace Kuick\Tests\Http\Server;

use Kuick\Http\Server\Router;
use Kuick\Http\MethodNotAllowedException;
use Kuick\Http\NotFoundException;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Kuick\Tests\Mocks\ControllerMock;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\Http\Server\Router
 */
class RouteTest extends TestCase
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
        $matcher = new Router(new NullLogger());
        $matcher->setRoutes(self::ROUTES);
        assertEquals(self::ROUTES, $matcher->getRoutes());
    }

    public function testIfOptionsGetsDefaultOptionsController(): void
    {
        $matcher = new Router(new NullLogger());
        $matcher->setRoutes(self::ROUTES);

        assertEquals([], $matcher->findRoute(new ServerRequest('OPTIONS', '/whatever')));
    }

    public function testIfOptionsReturnsEmptyRoute(): void
    {
        $matcher = new Router(new NullLogger());
        $matcher->setRoutes(self::ROUTES);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage('Not found');
        $matcher->findRoute(new ServerRequest('GET', '/inexistent'));
    }

    public function testIfRouterMatchesDefinedRoutes(): void
    {
        $matcher = new Router(new NullLogger());
        $matcher->setRoutes(self::ROUTES);

        assertEquals(
            self::ROUTES[0] + ['params' => []],
            $matcher->findRoute(new ServerRequest('GET', '/'))
        );

        assertEquals(
            self::ROUTES[1] + ['params' => ['userId' => 539]],
            $matcher->findRoute(new ServerRequest('PUT', '/api/user/539'))
        );

        assertEquals(
            self::ROUTES[2] + ['params' => ['message' => 'Some-Message-492']],
            $matcher->findRoute(new ServerRequest('GET', '/ping/Some-Message-492'))
        );
    }

    public function testIfMethodMismatchGivesThrowsMethodNotAllowed(): void
    {
        $matcher = new Router(new NullLogger());
        $matcher->setRoutes(self::ROUTES);

        $this->expectException(MethodNotAllowedException::class);
        $this->expectExceptionCode(405);
        $this->expectExceptionMessage('POST method is not allowed for path: /');
        $matcher->findRoute(new ServerRequest('POST', '/'));
    }
}
