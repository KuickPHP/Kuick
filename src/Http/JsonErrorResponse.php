<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http;

use Throwable;

class JsonErrorResponse extends JsonResponse
{
    private const ERROR_KEY = 'error';
    private const DEFAULT_MESSAGE = 'Internal server error';

    public function __construct(
        string $message = self::DEFAULT_MESSAGE,
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR
    ) {
        $code = $code == 0 ? Response::HTTP_INTERNAL_SERVER_ERROR : $code;
        try {
            parent::__construct([self::ERROR_KEY => $message], $code, [], false);
        } catch (Throwable $error) {
            parent::__construct([self::ERROR_KEY => $error->getMessage()], self::HTTP_INTERNAL_SERVER_ERROR, [], false);
        }
    }
}
