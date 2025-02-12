<?php

namespace Tests\Unit\Kuick\Framework\Config;

use Kuick\Framework\Config\ConfigException;
use Kuick\Framework\Config\RouteConfig;
use Kuick\Framework\Config\RouteConfigValidator;
use Tests\Unit\Kuick\Framework\Mocks\MockRoute;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers Kuick\Framework\Config\RouteConfigValidator
 */
class RouteConfigValidatorTest extends TestCase
{
    public function testIfCorrectRouteConfigValidatorDoesNothing(): void
    {
        $routeConfig = new RouteConfig('/test', MockRoute::class);
        (new RouteConfigValidator())->validate($routeConfig);
        $this->assertTrue(true);
    }

    public function testIfEmptyPathRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Route path should not be empty');
        (new RouteConfigValidator())->validate(new RouteConfig('', MockRoute::class));
    }

    public function testIfEmptyRouteClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Route controller class name should not be empty');
        (new RouteConfigValidator())->validate(new RouteConfig('/test', ''));
    }

    public function testIfInexistentRouteClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Route controller class: "InexistentRoute" does not exist');
        (new RouteConfigValidator())->validate(new RouteConfig('/test', 'InexistentRoute'));
    }

    public function testIfNotInvokableRouteClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Route controller class: "stdClass" is not invokable');
        (new RouteConfigValidator())->validate(new RouteConfig('/test', 'stdClass'));
    }

    public function testIfInvalidPatternRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Route path should be a valid regex pattern');
        (new RouteConfigValidator())->validate(new RouteConfig('([a-z][[a-z]', MockRoute::class));
    }

    public function testIfInvalidMethodRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Route method: INVALID is invalid, path: /test');
        (new RouteConfigValidator())->validate(new RouteConfig('/test', MockRoute::class, ['INVALID']));
    }
}
