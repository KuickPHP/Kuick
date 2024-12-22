<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Ops\Security;

use DI\Attribute\Inject;
use Kuick\Http\ForbiddenException;
use Kuick\Http\UnauthorizedException;
use OpenApi\Attributes\SecurityScheme;
use Psr\Http\Message\ServerRequestInterface;

#[SecurityScheme(securityScheme: 'Bearer Token', type: 'http', scheme: 'bearer')]
class OpsGuard
{
    private const AUTHORIZATION_HEADER = 'Authorization';
    private const BEARER_TOKEN_TEMPLATE = 'Bearer %s';

    public function __construct(#[Inject('kuick.ops.guard.token')] private string $opsToken)
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
