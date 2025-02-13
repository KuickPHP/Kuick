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
use Kuick\Security\Guardhouse;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;

use function DI\autowire;
use function DI\create;
use function DI\get;

// service definitions
return [
    Application::class => autowire(Application::class),
    EventDispatcherInterface::class => autowire(EventDispatcher::class),
    FallbackRequestHandlerInterface::class => create(JsonNotFoundRequestHandler::class),
    ListenerProviderInterface::class => autowire(ListenerProvider::class),

    RequestHandlerInterface::class => create(StackRequestHandler::class)
        ->constructor(
            get(FallbackRequestHandlerInterface::class)
        ),

    Guardhouse::class => function (LoggerInterface $logger, ConfigIndexer $configIndexer, ContainerInterface $container) {
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
    },

    SystemCacheInterface::class => autowire(SystemCache::class),
];
