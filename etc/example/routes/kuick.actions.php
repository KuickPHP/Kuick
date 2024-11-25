<?php

use Kuick\Example\UI\HelloAction;
use Kuick\Http\Request;
use Kuick\Ops\Security\OpsGuard;
use Kuick\Ops\UI\OpsAction;

return [
    //you probably want to remove this sample homepage
    [
        'pattern' => '/',
        //optional method, defaults to GET
        //'method' => Request::METHOD_GET,
        'action' => HelloAction::class,
    ],
    //this route is protected by Bearer Token (see the configuration file, and the OpsGuard)
    [
        'pattern' => '/api/ops',
        'action' => OpsAction::class,
        'guards' => [OpsGuard::class]
    ],
];
