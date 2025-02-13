<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

use Kuick\Framework\Listeners\EventLoggingListener;
use Kuick\Framework\Listeners\LocalizingListener;
use Kuick\Framework\Listeners\RequestHandlingListener;
use Kuick\Framework\Listeners\ResponseEmittingListener;
use Kuick\Framework\Api\Security\OpsGuard;
use Kuick\Framework\Api\UI\DocHtmlController;
use Kuick\Framework\Api\UI\DocJsonController;
use Kuick\Framework\Api\UI\OpsController;
use Kuick\Framework\Api\UI\OptionsController;
use Kuick\Framework\Config\ConfigIndexer;
use Kuick\Framework\Listeners\ExceptionHandlingListener;
use Kuick\Framework\Listeners\RegisteringPhpErrorHandlerListener;
use Kuick\Routing\RoutingMiddleware;
use Kuick\Security\SecurityMiddleware;

use function DI\autowire;

return [
    // performance optimization: autowiring
    // services
    ConfigIndexer::class => autowire(),

    // listeners
    EventLoggingListener::class => autowire(),
    ExceptionHandlingListener::class => autowire(),
    LocalizingListener::class => autowire(),
    RegisteringPhpErrorHandlerListener::class => autowire(),
    RequestHandlingListener::class => autowire(),
    ResponseEmittingListener::class => autowire(),

    // middlewares
    RoutingMiddleware::class => autowire(),
    SecurityMiddleware::class => autowire(),

    // UI
    DocHtmlController::class => autowire(),
    DocJsonController::class => autowire(),
    OpsController::class => autowire(),
    OpsGuard::class => autowire(),
    OptionsController::class => autowire(),
];
