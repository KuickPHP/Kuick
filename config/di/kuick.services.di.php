<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

use Kuick\Framework\SystemCache;
use Kuick\Framework\SystemCacheInterface;
use Kuick\EventDispatcher\EventDispatcher;
use Kuick\EventDispatcher\ListenerProvider;
use Kuick\Framework\Config\ConfigIndexer;
use Kuick\Http\Server\FallbackRequestHandlerInterface;
use Kuick\Http\Server\JsonNotFoundRequestHandler;
use Kuick\Http\Server\StackRequestHandler;
use Kuick\Routing\Router;
use Kuick\Routing\RoutingMiddleware;
use Kuick\Security\Guardhouse;
use Kuick\Security\SecurityMiddleware;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;

use function DI\autowire;
use function DI\create;

// service definitions
return [
    Application::class => create(Application::class),
    EventDispatcherInterface::class => autowire(EventDispatcher::class),
    FallbackRequestHandlerInterface::class => create(JsonNotFoundRequestHandler::class),
    ListenerProviderInterface::class => create(ListenerProvider::class),

    RequestHandlerInterface::class => function (
        FallbackRequestHandlerInterface $fallbackRequestHandler,
        SecurityMiddleware $securityMiddleware,
        RoutingMiddleware $routingMiddleware,
        LoggerInterface $logger
    ) {
        $requestHandler = (new StackRequestHandler($fallbackRequestHandler))
            ->addMiddleware($securityMiddleware)
            ->addMiddleware($routingMiddleware);
        $logger->info('RequestHandler initialized with Security and Routing middlewares');
        return $requestHandler;
    },

    Router::class => function (
        LoggerInterface $logger,
        ConfigIndexer $configIndexer,
        ContainerInterface $container
    ) {
        $router = new Router($logger);
        // adding routes to Router
        foreach ($configIndexer->getConfigFilePaths(ConfigIndexer::ROUTES_FILE_SUFFIX) as $routeConfigFile) {
            foreach (require $routeConfigFile as $routeConfig) {
                $logger->debug('Adding route: ' . $routeConfig->path, $routeConfig->methods);
                $router->addRoute(
                    $routeConfig->path,
                    $container->get($routeConfig->controllerClassName),
                    $routeConfig->methods
                );
            }
        }
        $logger->info('Router initialized');
        return $router;
    },

    Guardhouse::class => function (
        LoggerInterface $logger,
        ConfigIndexer $configIndexer,
        ContainerInterface $container
    ) {
        $guardhouse = new Guardhouse($logger);
        // adding guards to Guardhouse
        foreach ($configIndexer->getConfigFilePaths(ConfigIndexer::GUARDS_FILE_SUFFIX) as $guardConfigFile) {
            foreach (require $guardConfigFile as $guardConfig) {
                $logger->debug('Adding guard: ' . $guardConfig->path);
                $guardhouse->addGuard(
                    $guardConfig->path,
                    $container->get($guardConfig->guardClassName),
                    $guardConfig->methods
                );
            }
        }
        $logger->info('Guardhouse initialized');
        return $guardhouse;
    },

    SystemCacheInterface::class => autowire(SystemCache::class),
];
