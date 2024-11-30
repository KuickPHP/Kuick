<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http;

class JsonErrorResponse extends JsonResponse
{
    private const ERROR_KEY = 'error';
    private const DEFAULT_MESSAGE = 'Internal server error';

    public function __construct(
        string $message = self::DEFAULT_MESSAGE,
        int $code = ResponseCodes::INTERNAL_SERVER_ERROR
    ) {
        $code = $code == 0 ? ResponseCodes::INTERNAL_SERVER_ERROR : $code;
        parent::__construct([self::ERROR_KEY => $message], $code);
    }
}
