<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Events;

use Psr\Http\Message\ResponseInterface;

final class ResponseCreated
{
    public function __construct(private ResponseInterface $response)
    {
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
