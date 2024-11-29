<?php

namespace Tests\Kuick\App\Router;

use Kuick\App\Router\RouteValidator;
use Kuick\Http\InternalServerErrorException;
use PHPUnit\Framework\TestCase;
use Tests\Kuick\Mocks\ControllerMock;
use Tests\Kuick\Mocks\ForbiddenGuardMock;
use Tests\Kuick\Mocks\InvalidControllerMock;
use Tests\Kuick\Mocks\InvalidGuardMock;

use function PHPUnit\Framework\assertTrue;

/**
 * @covers \Kuick\App\Router\RouteValidator
 */
class RouteValidatorTest extends TestCase
{
    public function testProperValidationOfASimpleRoute(): void
    {
        $simpleRoute = [
            'path' => '/',
            'controller' => ControllerMock::class,
        ];
        (new RouteValidator())($simpleRoute);
        assertTrue(true);
    }

    public function testProperValidationOfAMoreSophisticatedRoute(): void
    {
        $simpleRoute = [
            'path' => '/hello/(?<name>[a-zA-Z-]+)/(?<age>[0-9]{1,3})',
            'method' => 'POST',
            'controller' => ControllerMock::class,
            'guards' => [ForbiddenGuardMock::class]
        ];
        (new RouteValidator())($simpleRoute);
        assertTrue(true);
    }

    public function testIfMissingPathThrowsException(): void
    {
        $brokenPath = [
            'controller' => ControllerMock::class,
            'guards' => [],
        ];
        $this->expectException(InternalServerErrorException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('One or more actions are missing path');
        (new RouteValidator())($brokenPath);
    }

    public function testIfInvalidTypeOfPathThrowsException(): void
    {
        $brokenPath = [
            'path' => ['shouldnt be an array'],
            'controller' => ControllerMock::class,
            'guards' => [],
        ];
        $this->expectException(InternalServerErrorException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('One or more actions has invalid path, should be a string');
        (new RouteValidator())($brokenPath);
    }

    public function testIfDuplicatePathThrowsException(): void
    {
        $brokenPath = [
            'path' => '/broken/(?<name>)(?<name>)',
            'controller' => ControllerMock::class,
        ];
        $this->expectException(InternalServerErrorException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Path invalid: /broken/(?<name>)(?<name>), preg_match(): Compilation failed: two named subpatterns have the same name (PCRE2_DUPNAMES not set) at offset 26');
        (new RouteValidator())($brokenPath);
    }

    public function testIfInvalidMethodTypeThrowsException(): void
    {
        $brokenPath = [
            'method' => ['should be a string'],
            'path' => '/',
            'controller' => ControllerMock::class,
        ];
        $this->expectException(InternalServerErrorException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Method is not a string, path: /');
        (new RouteValidator())($brokenPath);
    }

    public function testIfInvalidMethodThrowsException(): void
    {
        $brokenPath = [
            'method' => 'POSTSOMETHING',
            'path' => '/',
            'controller' => ControllerMock::class,
        ];
        $this->expectException(InternalServerErrorException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Method invalid, path: /');
        (new RouteValidator())($brokenPath);
    }

    public function testIfMissingControllerThrowsException(): void
    {
        $brokenPath = [
            'method' => 'POST',
            'path' => '/',
        ];
        $this->expectException(InternalServerErrorException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Missing controller class name, path: /');
        (new RouteValidator())($brokenPath);
    }

    public function testIfInvalidControllerTypeThrowsException(): void
    {
        $brokenPath = [
            'method' => 'POST',
            'path' => '/',
            'controller' => ['should be a string']
        ];
        $this->expectException(InternalServerErrorException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Controller class name is not a string, path: /');
        (new RouteValidator())($brokenPath);
    }

    public function testIfInexistentControllerThrowsException(): void
    {
        $brokenPath = [
            'method' => 'POST',
            'path' => '/',
            'controller' => 'InexistentClassController'
        ];
        $this->expectException(InternalServerErrorException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Controller: InexistentClassController" does not exist, path: /');
        (new RouteValidator())($brokenPath);
    }

    public function testIfControllerMissingInvokeThrowsException(): void
    {
        $brokenPath = [
            'method' => 'POST',
            'path' => '/',
            'controller' => InvalidControllerMock::class
        ];
        $this->expectException(InternalServerErrorException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Controller: Tests\Kuick\Mocks\InvalidControllerMock" is missing __invoke() method, path: /');
        (new RouteValidator())($brokenPath);
    }

    public function testIfMalformedGuadsThrowsException(): void
    {
        $brokenPath = [
            'path' => '/',
            'controller' => ControllerMock::class,
            'guards' => 'should be an array'
        ];
        $this->expectException(InternalServerErrorException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Guards malformed, not an array, path: /');
        (new RouteValidator())($brokenPath);
    }

    public function testIfASingleMalformedGuardThrowsException(): void
    {
        $brokenPath = [
            'path' => '/',
            'controller' => ControllerMock::class,
            'guards' => [ForbiddenGuardMock::class, ['should be a string']]
        ];
        $this->expectException(InternalServerErrorException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Guard class name is not a string, path: /');
        (new RouteValidator())($brokenPath);
    }

    public function testIfInexistentGuardClassThrowsException(): void
    {
        $brokenPath = [
            'path' => '/',
            'controller' => ControllerMock::class,
            'guards' => [ForbiddenGuardMock::class, 'InexistentGuard']
        ];
        $this->expectException(InternalServerErrorException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Guard: "InexistentGuard" does not exist, path: /');
        (new RouteValidator())($brokenPath);
    }

    public function testIfGuardMissingInvokeThrowsException(): void
    {
        $brokenPath = [
            'path' => '/',
            'controller' => ControllerMock::class,
            'guards' => [ForbiddenGuardMock::class, InvalidGuardMock::class]
        ];
        $this->expectException(InternalServerErrorException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Guard: "Tests\Kuick\Mocks\InvalidGuardMock" is missing __invoke() method, path: /');
        (new RouteValidator())($brokenPath);
    }
}
