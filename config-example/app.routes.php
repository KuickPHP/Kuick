<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

use Kuick\Framework\Config\RouteConfig;
use Kuick\Http\Message\JsonResponse;

// route configuration
return [
    // Inline home route
    new RouteConfig(
        '/',
        function (): JsonResponse {
            return new JsonResponse(['message' => 'Kuick says: hello world!']);
        },
    ),
    //new RouteConfig('/path', SomeController::class)
];
