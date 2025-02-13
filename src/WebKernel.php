<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework;

use Kuick\Framework\Config\ConfigIndexer;
use Kuick\Framework\Events\KernelCreatedEvent;
use Kuick\Routing\Router;
use Kuick\Routing\RoutingMiddleware;
use Kuick\Security\Guardhouse;
use Kuick\Security\SecurityMiddleware;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

/**
 * Web application Kernel
 */
class WebKernel extends KernelAbstract
{
    public function __construct(string $projectDir)
    {
        parent::__construct($projectDir);
        $logger = $this->getContainer()->get(LoggerInterface::class);
        $configIndexer = $this->getContainer()->get(ConfigIndexer::class);
        // adding guards to Guardhouse
        foreach ($configIndexer->getConfigFilePaths(ConfigIndexer::GUARDS_FILE_SUFFIX) as $guardConfigFile) {
            foreach (require $guardConfigFile as $guardConfig) {
                $logger->debug('Adding guard: ' . $guardConfig->path);
                $this->getContainer()->get(Guardhouse::class)->addGuard(
                    $guardConfig->path,
                    $this->getContainer()->get($guardConfig->guardClassName),
                    $guardConfig->methods
                );
            }
        }
        $logger->info('Guardhouse initialized');
        // adding routes to Router
        foreach ($configIndexer->getConfigFilePaths(ConfigIndexer::ROUTES_FILE_SUFFIX) as $routeConfigFile) {
            foreach (require $routeConfigFile as $routeConfig) {
                $logger->debug('Adding route: ' . $routeConfig->path, $routeConfig->methods);
                $this->getContainer()->get(Router::class)->addRoute(
                    $routeConfig->path,
                    $this->getContainer()->get($routeConfig->controllerClassName),
                    $routeConfig->methods
                );
            }
        }
        $logger->info('Router initialized');
        // registering request handler middlewares
        $this->getContainer()->get(RequestHandlerInterface::class)
            ->addMiddleware($this->getContainer()->get(SecurityMiddleware::class))
            ->addMiddleware($this->getContainer()->get(RoutingMiddleware::class));
        $logger->info('Request handler initialized');
        // dispatching KernelCreatedEvent
        $this->getContainer()->get(EventDispatcherInterface::class)->dispatch(new KernelCreatedEvent($this));
    }
}
