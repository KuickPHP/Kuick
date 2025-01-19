<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http\Server;

use Psr\Http\Message\ResponseInterface;

/**
 * Response Emitter
 */
class ResponseEmitter
{
    public function __invoke(ResponseInterface $response): void
    {
        //set response code
        http_response_code($response->getStatusCode());
        //send headers
        $this->sendHeaders($response);
        echo $response->getBody();
    }

    private function sendHeaders(ResponseInterface $response): void
    {
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }
    }
}
