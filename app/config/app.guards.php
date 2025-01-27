<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

use Kuick\Framework\Config\GuardConfig;
use Kuick\Http\Message\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

// guard configuration
return [
    // inline guard for /ping route
    new GuardConfig(
        '/ping',
        function (ServerRequestInterface $request): ?ResponseInterface {
            return $request->getHeaderLine('Authorization') ?
                null :
                new JsonResponse(['error' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
        }
    ),
];
