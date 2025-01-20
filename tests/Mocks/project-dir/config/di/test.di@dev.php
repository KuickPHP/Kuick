<?php

//overrides test.di.php

use function DI\env;

return [
    'kuick.app.timezone'  => env('KUICK_APP_TIMEZONE', 'Europe/Warsaw'),
    'kuick.app.monolog.usemicroseconds' => env('KUICK_APP_MONOLOG_USEMICROSECONDS', true),
];
