<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Security;

use Kuick\Http\Request;

/**
 * Provides actions with optional security layer, like: header validation, request filterint etc.
 * Throw one of HttpExceptions if something is wrong with the request
 */
interface GuardInterface
{
    public function __invoke(Request $request): void;
}
