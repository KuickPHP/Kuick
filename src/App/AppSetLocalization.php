<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use DI\Attribute\Inject;

/**
 * Locale configurator
 */
final class AppSetLocalization
{
    private const DEFAULT_LOCALE = 'en_US.utf-8';

    public function __construct(
        #[Inject('kuick.app.locale')] private string $locale,
        #[Inject('kuick.app.timezone')] private string $timezone,
        #[Inject('kuick.app.charset')] private string $charset,
    ) {
    }

    public function __invoke()
    {
        mb_internal_encoding($this->charset);
        ini_set('default_charset', $this->charset);
        date_default_timezone_set($this->timezone);
        ini_set('date.timezone', $this->timezone);
        setlocale(LC_ALL, $this->locale);
        //numbers are always localized to en_US.utf-8'
        setlocale(LC_NUMERIC, self::DEFAULT_LOCALE);
    }
}
