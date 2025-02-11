<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\Api\Security;

use DI\Attribute\Inject;
use Kuick\Http\Message\JsonResponse;
use OpenApi\Attributes\SecurityScheme;
use Psr\Http\Message\ServerRequestInterface;

#[SecurityScheme(securityScheme: 'Bearer Token', type: 'http', scheme: 'bearer')]
class OpsGuard
{
    private const AUTHORIZATION_HEADER = 'Authorization';
    private const BEARER_TOKEN_TEMPLATE = 'Bearer %s';

    public function __construct(#[Inject('api.security.ops.guard.token')] private string $opsToken)
    {
    }

    public function __invoke(ServerRequestInterface $request): ?JsonResponse
    {
        $requestToken = $request->getHeaderLine(self::AUTHORIZATION_HEADER);
        if (!$requestToken) {
            return new JsonResponse(['error' => 'Token not found'], JsonResponse::HTTP_UNAUTHORIZED);
        }
        $expectedToken = sprintf(self::BEARER_TOKEN_TEMPLATE, $this->opsToken);
        //token mismatch
        if ($requestToken != $expectedToken) {
            return new JsonResponse(['error' => 'Token invalid'], JsonResponse::HTTP_FORBIDDEN);
        }
        return null;
    }
}
