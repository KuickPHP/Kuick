<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\DIFactories;

use FilesystemIterator;
use GlobIterator;
use Kuick\App\AppDIContainerBuilder;
use Kuick\App\DIFactories\Utils\ClassInvokeArgumentReflector;
use Kuick\App\DIFactories\Utils\RouteValidator;
use Kuick\App\KernelAbstract;
use Kuick\Http\Server\Router;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 *
 */
class BuildRouter extends FactoryAbstract
{
    public const CACHE_FILE = '/kuick-app-routes.php';
    public const ROUTE_LOCATIONS = [
        '/vendor/kuick/*/config/*.routes.php',
        '/config/*.routes.php',
    ];

    /**
     * @SuppressWarnings(PHPMD.ErrorControlOperator)
     */
    public function __invoke(): void
    {
        $this->builder->addDefinitions([Router::class => function (ContainerInterface $container): Router {
            $logger = $container->get(LoggerInterface::class);
            $projectDir = $container->get(AppDIContainerBuilder::PROJECT_DIR_CONFIGURATION_KEY);
            $cacheFile = $projectDir . AppDIContainerBuilder::CACHE_PATH . BuildRouter::CACHE_FILE;
            $cachedRoutes = @include $cacheFile;
            $routes = [];
            if (
                !empty($cachedRoutes) &&
                KernelAbstract::ENV_PROD === $container->get(AppDIContainerBuilder::APP_ENV_CONFIGURATION_KEY)
            ) {
                $logger->debug('Routes loaded from cache');
                $routes = $cachedRoutes;
            }
            if (empty($routes)) {
                //@TODO: extract route parsing to the external class
                //app config (normal priority)
                foreach (BuildRouter::ROUTE_LOCATIONS as $routeLocation) {
                    $routeIterator = new GlobIterator($projectDir . $routeLocation, FilesystemIterator::KEY_AS_FILENAME);
                    foreach ($routeIterator as $routeFile) {
                        $routes = array_merge($routes, include $routeFile);
                    }
                }
                //validating routes
                //decorating routes with available controller arguments
                foreach ($routes as $routeKey => $route) {
                    (new RouteValidator())($route);
                    $routes[$routeKey]['arguments'][$route['controller']] = (new ClassInvokeArgumentReflector())($route['controller']);
                    if (!isset($route['guards'])) {
                        continue;
                    }
                    foreach ($route['guards'] as $guard) {
                        $routes[$routeKey]['arguments'][$guard] = (new ClassInvokeArgumentReflector())($guard);
                    }
                }
                if (!file_exists(dirname($cacheFile))) {
                    mkdir(dirname($cacheFile));
                }
                file_put_contents($cacheFile, sprintf('<?php return %s;', var_export($routes, true)));
                $logger->notice('Routes analyzed, cache written');
            }
            return (new Router($container->get(LoggerInterface::class)))->setRoutes($routes);
        }]);
    }
}
