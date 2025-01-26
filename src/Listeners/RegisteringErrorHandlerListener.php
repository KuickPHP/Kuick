<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\Listeners;

use Exception;
use Kuick\Framework\Events\ExceptionRaisedEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

final class RegisteringErrorHandlerListener
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private LoggerInterface $logger
    )
    {
    }

    public function __invoke(): void
    {
        //error handler
        set_error_handler(function ($errno, $errstr, $errfile, $errline): void {
            throw new Exception($errstr . ' [' . $errline. '] ' , $errfile . ' ' . $errno);
        });
        set_exception_handler(function (Exception $exception): void {
            $this->eventDispatcher->dispatch(new ExceptionRaisedEvent($exception));
        });
        $this->logger->info('Error handler registered');
    }
}
