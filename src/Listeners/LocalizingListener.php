<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\Listeners;

use DI\Attribute\Inject;
use Psr\Log\LoggerInterface;

final class LocalizingListener
{
    private const DEFAULT_LOCALE = 'en_US.utf-8';

    public function __construct(
        #[Inject('kuick.app.locale')] private string $locale,
        #[Inject('kuick.app.timezone')] private string $timezone,
        #[Inject('kuick.app.charset')] private string $charset,
        private LoggerInterface $logger,
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
        $this->logger->info('Locale setup:', [
            'locale' => $this->locale,
            'timezone' => $this->timezone,
            'charset' => $this->charset,
        ]);
    }
}
