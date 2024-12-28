<?php

use Kuick\Example\UI\PingController;

return [
    //example homepage
    [
        'path' => '/',
        //optional method, defaults to GET
        //'method' => 'GET',
        'controller' => PingController::class,
    ],
    //route contains named parameter, see how the PingController handles such request
    [
        'path' => '/hello/(?<name>[a-zA-Z0-9-]{1,40})',
        'controller' => PingController::class,
    ],
];