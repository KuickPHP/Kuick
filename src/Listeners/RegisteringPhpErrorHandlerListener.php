<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\Listeners;

use ErrorException;
use Kuick\Framework\Events\ExceptionRaisedEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final class RegisteringPhpErrorHandlerListener
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @codeCoverageIgnore
     */
    public function __invoke()
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
}
