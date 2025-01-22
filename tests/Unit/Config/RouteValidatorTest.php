<?php

namespace Tests\Unit\Kuick\Framework\Config;

use Kuick\Framework\Config\ConfigException;
use Kuick\Framework\Config\RouteConfig;
use Kuick\Framework\Config\RouteValidator;
use Tests\Unit\Kuick\Framework\Mocks\MockRoute;
use PHPUnit\Framework\TestCase;

/**
 * @covers Kuick\Framework\Config\RouteValidator
 */
class RouteValidatorTest extends TestCase
{
    public function testIfCorrectRouteValidatorDoesNothing(): void
    {
        $routeConfig = new RouteConfig('/test', MockRoute::class);
        new RouteValidator($routeConfig);
        $this->assertTrue(true);
    }

    public function testIfEmptyPathRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Route path should not be empty');
        new RouteValidator(new RouteConfig('', MockRoute::class));
    }

    public function testIfEmptyRouteClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Route controller class name should not be empty');
        new RouteValidator(new RouteConfig('/test', ''));
    }

    public function testIfInexistentRouteClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Route controller class: "InexistentRoute" does not exist');
        new RouteValidator(new RouteConfig('/test', 'InexistentRoute'));
    }

    public function testIfNotInvokableRouteClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Route controller class: "stdClass" is not invokable');
        new RouteValidator(new RouteConfig('/test', 'stdClass'));
    }

    public function testIfInvalidPatternRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Route path should be a valid regex pattern');
        new RouteValidator(new RouteConfig('([a-z][[a-z]', MockRoute::class));
    }

    public function testIfInvalidMethodRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Route method: INVALID is invalid, path: /test');
        new RouteValidator(new RouteConfig('/test', MockRoute::class, ['INVALID']));
    }
}
