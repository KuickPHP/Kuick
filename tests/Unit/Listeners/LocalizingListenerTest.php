<?php

namespace Tests\Unit\Kuick\Framework\Listeners;

use Kuick\Framework\Listeners\LocalizingListener;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers Kuick\Framework\Listeners\LocalizingListener
 */
class LocalizingListenerTest extends TestCase
{
    public function testIfLocalizationIsSet(): void
    {
        (new LocalizingListener('pl_PL.utf-8', 'Europe/Warsaw', 'UTF-8'))();
        assertEquals('UTF-8', ini_get('default_charset'));
        assertEquals('Europe/Warsaw', ini_get('date.timezone'));
        assertEquals('Europe/Warsaw', date_default_timezone_get());
        assertEquals('UTF-8', mb_internal_encoding());
    }
}
