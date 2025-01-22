<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Example\UI;

use Kuick\Http\Message\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

class PingController
{
    private const DEFAULT_NAME = 'my friend';

    public function __invoke(ServerRequestInterface $request): JsonResponse
    {
        $name = $request->getQueryParams()['name'] ?? self::DEFAULT_NAME;
        $message = ['message' => 'Kuick says: hello ' . $name . '!'];
        if (self::DEFAULT_NAME === $name) {
            $message['hint'] = 'If you want a proper greeting use: /hello/Name';
        }
        return new JsonResponse($message);
    }
}
