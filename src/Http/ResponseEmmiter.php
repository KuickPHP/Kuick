<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http;

use Psr\Http\Message\ResponseInterface;

class ResponseEmmiter
{
    public function __invoke(ResponseInterface $response): void
    {
        //create a status line if no headers are present, and code is not 200
        if (empty($response->getHeaders()) && $response->getStatusCode() != ResponseCodes::OK) {
            $statusLine = sprintf('HTTP/%s %s', $response->getProtocolVersion(), $response->getStatusCode());
            header($statusLine);
        }
        //send headers
        foreach (array_keys($response->getHeaders()) as $headerName) {
            //format header
            $responseHeader = sprintf('%s: %s', $headerName, $response->getHeaderLine($headerName));
            //send header
            $response->getStatusCode() == ResponseCodes::OK ?
                header($responseHeader, false) :
                header($responseHeader, false, $response->getStatusCode());
        }
        echo $response->getBody();
    }
}
