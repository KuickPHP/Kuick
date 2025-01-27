<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework;

use ErrorException;
use Kuick\Framework\DependencyInjection\ContainerCreator;
use Kuick\Framework\Events\ExceptionRaisedEvent;
use Kuick\Framework\Events\KernelCreatedEvent;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Application Kernel
 */
class Kernel implements KernelInterface
{
    private const DEFAULT_LOCALE = 'en_US.utf-8';
    private const DI_LOCALE_KEY = 'kuick.app.locale';
    private const DI_TIMEZONE_KEY = 'kuick.app.timezone';
    private const DI_CHARSET_KEY = 'kuick.app.charset';

    private ContainerInterface $container;
    private EventDispatcherInterface $eventDispatcher;
    private LoggerInterface $logger;

    public function __construct(private string $projectDir)
    {
        // building DI container
        $this->container = (new ContainerCreator())($projectDir);
        $this->eventDispatcher = $this->container->get(EventDispatcherInterface::class);
        $this->logger = $this->container->get(LoggerInterface::class);
        // localizing application
        $this->localize(
            $this->container->get(self::DI_CHARSET_KEY),
            $this->container->get(self::DI_TIMEZONE_KEY),
            $this->container->get(self::DI_LOCALE_KEY)
        );
        $listenerProvider = $this->container->get(ListenerProviderInterface::class);
        // registering listeners "on the fly", as they can depend on EventDispatcher
        foreach ($this->container->get(self::DI_LISTENERS_KEY) as $listener) {
            $listenerProvider->registerListener($listener->pattern, $this->container->get($listener->listenerClassName), $listener->priority);
        }
        // register PHP Errors
        $this->registerPhpErrorsAndExceptionHandlers();
        $this->eventDispatcher->dispatch(new KernelCreatedEvent($this));
    }

    public function getProjectDir(): string
    {
        return $this->projectDir;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @codeCoverageIgnore
     */
    private function registerPhpErrorsAndExceptionHandlers(): void
    {
        // register error handler
        set_error_handler(function ($errno, $errstr, $errfile, $errline): void {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });
        // register exception handler
        set_exception_handler(function (Throwable $throwable): void {
            $this->eventDispatcher->dispatch(new ExceptionRaisedEvent($throwable));
        });
        $this->logger->info('PHP error and exception handlers registered');
    }

    private function localize(string $charset, string $timezone, string $locale): void
    {
        mb_internal_encoding($charset);
        ini_set('default_charset', $charset);
        date_default_timezone_set($timezone);
        ini_set('date.timezone', $timezone);
        setlocale(LC_ALL, $locale);
        //numbers are always localized to en_US.utf-8'
        setlocale(LC_NUMERIC, self::DEFAULT_LOCALE);
        $this->logger->info('Locale setup', [
            'locale' => $locale,
            'timezone' => $timezone,
            'charset' => $charset,
        ]);
    }
}
