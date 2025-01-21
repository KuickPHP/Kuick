<?php

namespace Tests\Kuick\Unit\Security;

use Kuick\Http\BadRequestException;
use PHPUnit\Framework\TestCase;
use Kuick\Security\ExecutableGuard;
use Nyholm\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @covers \Kuick\Security\ExecutableGuard
 */
class ExecutableGuardTest extends TestCase
{
    public function testIfEmptyGuardPassesSilently(): void
    {
        $okGuardMock = function (): void {
        };
        //should do nothing (no exception should be raised)
        $executableGuard = new ExecutableGuard('/test', $okGuardMock, ['GET']);
        $executableGuard->execute(new ServerRequest('GET', '/test', []));
        $this->assertTrue(true);
    }

    public function testIfFailingGuardRaisesException(): void
    {
        $failingGuardMock = function (ServerRequestInterface $request): void {
            if ($request->getQueryParams()['some-param'] !== 'some-value') {
                return;
            }
            throw new BadRequestException($request->getBody()->getContents());
        };
        $failedGuard = (new ExecutableGuard('/test', $failingGuardMock, ['GET']))
            ->setParams(['some-param' => 'some-value']);

        $this->expectException(BadRequestException::class);

        $this->expectExceptionMessage('ooops');
        $failedGuard->execute(new ServerRequest('GET', '/test', [], 'ooops'));
    }
}
