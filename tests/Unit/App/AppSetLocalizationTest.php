<?php

namespace Tests\Kuick\App;

use Kuick\App\AppSetLocalization;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\App\AppSetLocalization
 */
class AppSetLocalizationTest extends TestCase
{
    public function testIfLocalizationIsSet(): void
    {
        (new AppSetLocalization('pl_PL.utf-8', 'Europe/Warsaw', 'UTF-8'))();
        assertEquals('UTF-8', ini_get('default_charset'));
        assertEquals('Europe/Warsaw', ini_get('date.timezone'));
        assertEquals('Europe/Warsaw', date_default_timezone_get());
        assertEquals('UTF-8', mb_internal_encoding());
    }
}
