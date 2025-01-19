<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http\Message;

class JsonResponse extends Response
{
    private const DEFAULT_HEADER = ['Content-Type' => 'application/json'];

    public function __construct(array $body, int $code = self::HTTP_OK, array $headers = [])
    {
        parent::__construct($code, array_merge($headers, self::DEFAULT_HEADER), json_encode($body));
    }
}
