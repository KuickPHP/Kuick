<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\App\JsonKernel;
use Kuick\Http\Request;

define('BASE_PATH', dirname(__DIR__));
require BASE_PATH . '/vendor/autoload.php';

(new JsonKernel())(Request::createFromGlobals());
