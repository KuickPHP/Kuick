<?php

use Kuick\App\Config\Route;
use Kuick\Example\UI\PingController;

return [
    //example homepage
    new Route('/', PingController::class),
    //route contains named parameter, see how the PingController handles such request
    new Route('/hello/(?<name>[a-zA-Z0-9-]{1,40})', PingController::class),
];