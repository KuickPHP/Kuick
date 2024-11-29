<?php

namespace Tests\Kuick\Ops\Security;

use Kuick\Http\ForbiddenException;
use Kuick\Http\UnauthorizedException;
use Kuick\Ops\Security\OpsGuard;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEmpty;

/**
 * @covers \Kuick\Ops\Security\OpsGuard
 */
class OpsGuardTest extends TestCase
{
    public function testIfQuitsGracefullyGivenAValidToken(): void
    {
        $guard = new OpsGuard('let-me-in');
        $request = (new ServerRequest('GET', '/'))
            ->withAddedHeader('Authorization', 'Bearer let-me-in');
        assertEmpty($guard($request));
    }

    public function testIfMissingTokenThrowsUnauthorized(): void
    {
        $guard = new OpsGuard('let-me-in');
        $request = (new ServerRequest('GET', '/'));
        $this->expectException(UnauthorizedException::class);
        $guard($request);
    }

    public function testIfInvalidTokenThrowsForbidden(): void
    {
        $guard = new OpsGuard('let-me-in');
        $request = (new ServerRequest('GET', '/'))
            ->withAddedHeader('Authorization', 'Bearer invalid-token');
        $this->expectException(ForbiddenException::class);
        $guard($request);
    }
}