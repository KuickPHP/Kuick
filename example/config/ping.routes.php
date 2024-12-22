<?php

use Kuick\Example\UI\PingController;

return [
    //you probably want to remove this sample homepage
    [
        'path' => '/',
        //optional method, defaults to GET
        //'method' => 'GET',
        'controller' => PingController::class,
    ],
    //named parameter see how the PingController handles such request
    [
        'path' => '/hello/(?<name>[a-zA-Z0-9-]{1,40})',
        'controller' => PingController::class,
    ],
];