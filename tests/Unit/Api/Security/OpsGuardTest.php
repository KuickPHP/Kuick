<?php

namespace Tests\Unit\Kuick\Framework\Api\Security;

use Kuick\Framework\Api\Security\OpsGuard;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

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
        $this->assertTrue(true);
    }

    public function testIfMissingTokenThrowsUnauthorized(): void
    {
        $guard = new OpsGuard('let-me-in');
        $request = (new ServerRequest('GET', '/'));
        $response = $guard($request);
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testIfInvalidTokenThrowsForbidden(): void
    {
        $guard = new OpsGuard('let-me-in');
        $request = (new ServerRequest('GET', '/'))
            ->withAddedHeader('Authorization', 'Bearer invalid-token');
        $response = $guard($request);
        $this->assertEquals(403, $response->getStatusCode());
    }
}
