<?php

namespace Tests\Unit\Kuick\Framework\Api\Security;

use Kuick\Http\ForbiddenException;
use Kuick\Http\UnauthorizedException;
use Kuick\Framework\Api\Security\OpsGuard;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertTrue;

/**
 * @covers Kuick\Framework\Api\Security\OpsGuard
 */
class OpsGuardTest extends TestCase
{
    public function testIfQuitsGracefullyGivenAValidToken(): void
    {
        $guard = new OpsGuard('let-me-in');
        $request = (new ServerRequest('GET', '/'))
            ->withAddedHeader('Authorization', 'Bearer let-me-in');
        $guard($request);
        assertTrue(true);
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
