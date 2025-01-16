<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\App\DotEnvLoader;
use Kuick\App\Events\RequestReceived;
use Kuick\App\Kernel;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

$projectDir = dirname(__DIR__);
require $projectDir . '/vendor/autoload.php';

// import .env (not recommended from the performance perspective)
new DotEnvLoader($projectDir);

$psr17Factory = new Psr17Factory();

$request = (new ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory, // StreamFactory
))->fromGlobals();

(new Kernel($projectDir))
    ->getEventDispatcher()
    ->dispatch(new RequestReceived($request));
