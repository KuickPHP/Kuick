<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Ops\Security;

use DI\Attribute\Inject;
use Kuick\Http\ForbiddenException;
use Kuick\Http\UnauthorizedException;
use Kuick\Security\GuardInterface;
use Psr\Http\Message\ServerRequestInterface;

class OpsGuard implements GuardInterface
{
    private const AUTHORIZATION_HEADER = 'Authorization';
    private const BEARER_TOKEN_TEMPLATE = 'Bearer %s';

    public function __construct(#[Inject('kuick.app.ops.guards.token')] private string $opsToken)
    {
    }

    public function __invoke(ServerRequestInterface $request): void
    {
        $requestToken = $request->getHeaderLine(self::AUTHORIZATION_HEADER);
        if (!$requestToken) {
            throw new UnauthorizedException('Token not found');
        }
        $expectedToken = sprintf(self::BEARER_TOKEN_TEMPLATE, $this->opsToken);
        //token mismatch
        if ($requestToken != $expectedToken) {
            throw new ForbiddenException('Token invalid');
        }
    }
}
