<?php

namespace Tests\Kuick\App\Router;

use Kuick\App\Router\ActionLauncher;
use Kuick\Http\ForbiddenException;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\NullLogger;
use Tests\Kuick\Mocks\ContainerMock;
use Tests\Kuick\Mocks\ControllerMock;
use Tests\Kuick\Mocks\EmptyGuardMock;
use Tests\Kuick\Mocks\ForbiddenGuardMock;
use Tests\Kuick\Mocks\RequestDependentControllerMock;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\App\Router\ActionLauncher
 */
class ActionLauncherTest extends TestCase
{
    public function testIfEmptyRouteGivesImmediateNoContentResponse(): void
    {
        $al = new ActionLauncher(new ContainerMock(), new NullLogger);
        $response = $al->__invoke([], new ServerRequest('OPTIONS', '/whatever'));
        assertEquals(204, $response->getStatusCode());
        assertEquals('', $response->getBody()->getContents());
    }

    public function testIfForbiddenGuardProtectsTheAction(): void
    {
        //add controller to the container
        $container = new ContainerMock([
            ControllerMock::class => new ControllerMock,
            ForbiddenGuardMock::class => new ForbiddenGuardMock,
        ]);
        $al = new ActionLauncher($container, new NullLogger);
        $this->expectException(ForbiddenException::class);
        $al->__invoke([
            'method' => 'PUT',
            'guards' => [ForbiddenGuardMock::class],
        ], new ServerRequest('GET', '/'));
    }

    public function testIfRunningAMockActionGives200JsonResponse(): void
    {
        //add controller and guard to the container
        $container = new ContainerMock([
            ControllerMock::class => new ControllerMock,
            ForbiddenGuardMock::class => new ForbiddenGuardMock,
        ]);
        $al = new ActionLauncher($container, new NullLogger);
        $response = $al->__invoke([
            'method' => 'PUT',
            'path' => '/api/user/(?<userId>[0-9]{1,8})',
            'invokeParams' => ['userId' => ['type' => 'int', 'default' => null]],
            'params' => ['userId' => 1234],
            'controller' => ControllerMock::class,
        ], new ServerRequest('PUT', '/api/user/1234'));
        assertEquals(200, $response->getStatusCode());
        assertEquals('{"userId":1234}', $response->getBody()->getContents());
    }

    public function testIfServerResponse(): void
    {
        //add controller and empty guard to the container
        $container = new ContainerMock([
            RequestDependentControllerMock::class => new RequestDependentControllerMock,
            EmptyGuardMock::class => new EmptyGuardMock,
        ]);
        $al = new ActionLauncher($container, new NullLogger);
        $response = $al->__invoke([
            'method' => 'GET',
            'path' => '/',
            'guards' => [EmptyGuardMock::class],
            'invokeParams' => ['request' => ['type' => ServerRequestInterface::class, 'default' => null]],
            'controller' => RequestDependentControllerMock::class,
        ], new ServerRequest('GET', '/?test=123&another=321'));
        assertEquals(200, $response->getStatusCode());
        assertEquals('{"queryParams":{"test":"123","another":"321"}}', $response->getBody()->getContents());
    }
}
