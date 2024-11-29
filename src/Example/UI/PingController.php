<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Example\UI;

use Kuick\Http\JsonResponse;

class PingController
{
    private const DEFAULT_NAME = 'my friend';

    public function __invoke(string $name = self::DEFAULT_NAME): JsonResponse
    {
        $message = ['message' => 'Kuick says: hello ' . $name . '!'];
        if (self::DEFAULT_NAME === $name) {
            $message['hint'] = 'If you want a proper greeting use: /hello/Name';
        }
        return new JsonResponse($message);
    }
}
