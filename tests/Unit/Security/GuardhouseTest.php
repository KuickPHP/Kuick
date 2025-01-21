<?php

namespace Tests\Kuick\Unit\Security;

use Kuick\Security\Guardhouse;
use Kuick\Security\ExecutableGuard;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * @covers \Kuick\Security\Guardhouse
 */
class GuardhouseTest extends TestCase
{
    public function testAddingAndMatchingASingleGuard(): void
    {
        $guardMock = function () {
        };
        $guardhouse = (new Guardhouse(new NullLogger()))
            ->addGuard('/test', $guardMock, ['GET', 'POST']);

        $executableGuard = $guardhouse->matchGuards(new ServerRequest('GET', '/test'))[0];
        $this->assertInstanceOf(ExecutableGuard::class, $executableGuard);
        $this->assertEquals(['GET', 'POST'], $executableGuard->methods);

        //empty guards
        $this->assertEmpty($guardhouse->matchGuards(new ServerRequest('GET', '/not-found')));
    }

    public function testAddingAndMatchingMultipleGuards(): void
    {
        $controllerMock = function () {
        };
        $guardhouse = (new Guardhouse(new NullLogger()))
            ->addGuard('/sample', $controllerMock, ['GET'])
            ->addGuard('/sample', $controllerMock, ['POST'])
            ->addGuard('/test', $controllerMock, ['GET', 'POST']);

        $executableGuard = $guardhouse->matchGuards(new ServerRequest('POST', '/test'))[0];
        $this->assertInstanceOf(ExecutableGuard::class, $executableGuard);
        $this->assertEquals(['GET', 'POST'], $executableGuard->methods);

        //method differs
        $this->assertEmpty($guardhouse->matchGuards(new ServerRequest('PUT', '/test')));
    }
}
