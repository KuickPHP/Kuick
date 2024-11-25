<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Example\UI;

use Kuick\Http\JsonResponse;
use Kuick\Http\Request;
use Kuick\UI\ActionInterface;

class HelloAction implements ActionInterface
{
    private const DEFAULT_NAME = 'my friend';

    public function __invoke(Request $request): JsonResponse
    {
        $name = ($request->query->get('name') ? $request->query->get('name') : self::DEFAULT_NAME);
        $message = [
            'message' => 'Kuick says: hello ' . $name . '!',
        ];
        if (!$request->query->get('name')) {
            $message['hint'] = 'If you want a proper greeting use: ' .$request->getUri(). '?name=Your-name';
        }
        return new JsonResponse($message);
    }
}
