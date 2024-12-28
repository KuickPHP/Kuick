<?php

namespace Kuick\Tests\Http\Server;

use Kuick\App\Router\ActionLauncher;
use Kuick\Http\ForbiddenException;
use Kuick\Http\Server\ActionHandler;
use Kuick\Http\Server\Router;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\NullLogger;
use Kuick\Tests\Mocks\ContainerMock;
use Kuick\Tests\Mocks\ControllerMock;
use Kuick\Tests\Mocks\EmptyGuardMock;
use Kuick\Tests\Mocks\ForbiddenGuardMock;
use Kuick\Tests\Mocks\RequestDependentControllerMock;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\Http\Server\ActionHandler
 */
class ActionHandlerTest extends TestCase
{
    public function testIfEmptyRouteGivesImmediateNoContentResponse(): void
    {
        $launcher = new ActionHandler(new ContainerMock(), new Router(new NullLogger()), new NullLogger());
        $response = $launcher([], new ServerRequest('OPTIONS', '/whatever'));
        assertEquals(204, $response->getStatusCode());
        assertEquals('', $response->getBody()->getContents());
    }

    public function testIfForbiddenGuardProtectsTheAction(): void
    {
        //add controller to the container
        $container = new ContainerMock([
            ControllerMock::class => new ControllerMock(),
            ForbiddenGuardMock::class => new ForbiddenGuardMock(),
        ]);
        $routes = [
            'path' => '/',
            'method' => 'PUT',
            'guards' => [ForbiddenGuardMock::class],
            'arguments' => [ForbiddenGuardMock::class => []],
        ];
        $router = new Router(new NullLogger());
        $router->setRoutes($routes);
        $handler = new ActionHandler($container, $router, new NullLogger());
        $this->expectException(ForbiddenException::class);
        $handler->handle(new ServerRequest('PUT', '/'));
    }

    /*public function testIfRunningAMockActionGives200JsonResponse(): void
    {
        //add controller and guard to the container
        $container = new ContainerMock([
            ControllerMock::class => new ControllerMock(),
            ForbiddenGuardMock::class => new ForbiddenGuardMock(),
        ]);
        $launcher = new ActionLauncher($container, new NullLogger());
        $response = $launcher([
            'method' => 'PUT',
            'path' => '/api/user/(?<userId>[0-9]{1,8})',
            //provided by route matcher
            'params' => ['userId' => 1234],
            'controller' => ControllerMock::class,
            //provided by reflection
            'arguments' => [
                ControllerMock::class => ['userId' => ['type' => 'int', 'default' => null]],
                ForbiddenGuardMock::class => [],
            ],
        ], new ServerRequest('PUT', '/api/user/1234'));
        assertEquals(200, $response->getStatusCode());
        assertEquals('{"userId":1234}', $response->getBody()->getContents());
    }

    public function testIfServerResponse(): void
    {
        //add controller and empty guard to the container
        $container = new ContainerMock([
            RequestDependentControllerMock::class => new RequestDependentControllerMock(),
            EmptyGuardMock::class => new EmptyGuardMock(),
        ]);
        $launcher = new ActionLauncher($container, new NullLogger());
        $response = $launcher([
            'method' => 'GET',
            'path' => '/',
            'guards' => [EmptyGuardMock::class],
            'controller' => RequestDependentControllerMock::class,
            //provided by reflection
            'arguments' => [
                RequestDependentControllerMock::class => ['request' => ['type' => ServerRequestInterface::class, 'default' => null]],
                EmptyGuardMock::class => ['message' => ['type' => 'string', 'default' => '', 'optional' => true]],
            ],
        ], new ServerRequest('GET', '/?test=123&another=321'));
        assertEquals(200, $response->getStatusCode());
        assertEquals('{"queryParams":{"test":"123","another":"321"}}', $response->getBody()->getContents());
    }*/
}
