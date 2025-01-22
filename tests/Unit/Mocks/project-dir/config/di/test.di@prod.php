<?php

//overrides test.di.php

use function DI\env;

return [
    'kuick.app.timezone'  => env('KUICK_APP_TIMEZONE', 'Europe/Paris'),
];
