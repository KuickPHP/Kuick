<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\DependencyInjection;

use APCUIterator;
use DI\ContainerBuilder;
use Kuick\Framework\Kernel;
use Kuick\Framework\KernelInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class ContainerCreator
{
    private const CACHE_PATH =  '/var/cache';
    private const COMPILED_FILENAME = 'CompiledContainer.php';

    public function __invoke(string $projectDir): ContainerInterface
    {
        //setting default env
        (false === getenv(Kernel::APP_ENV)) && putenv(Kernel::APP_ENV . '=' . Kernel::ENV_PROD);
        $appEnv = getenv(Kernel::APP_ENV);

        //remove container if not in production mode
        if (Kernel::ENV_DEV === $appEnv) {
            $this->removeContainer($projectDir, $appEnv);
        }

        //build or load from cache
        $container = $this->configureBuilder($projectDir, $appEnv)->build();

        //for production, return cached container if exists
        if ($container->has(Kernel::DI_PROJECT_DIR_KEY)) {
            $container->get(LoggerInterface::class)->info('Application is running in "' . $appEnv . '" mode, container loaded from cache');
            return $container;
        }
        //rebuilding if validation failed
        $this->removeContainer($projectDir, $appEnv);
        $container = $this->rebuildContainer($projectDir, $appEnv);
        $logger = $container->get(LoggerInterface::class);
        $logger->warning('DI container rebuilt, cache written, application is running in "' . $appEnv . '" mode');
        return $container;
    }

    private function rebuildContainer(string $projectDir, string $appEnv): ContainerInterface
    {
        $builder = $this->configureBuilder($projectDir, $appEnv);

        //adding default definitions
        $builder->addDefinitions([
            KernelInterface::DI_APP_ENV_KEY => $appEnv,
            KernelInterface::DI_PROJECT_DIR_KEY => $projectDir,
        ]);

        // building request handler
        (new RequestHandlerBuilder($builder))();

        // creating logger
        (new LoggerBuilder($builder))();

        // creating listeners list (used by Kernel)
        (new ListenersBuilder($builder))();

        // creating router matcher
        (new RouterBuilder($builder))();

        // creating guardhouse
        (new GuardhouseBuilder($builder))();

        // creating console application
        (new ConsoleApplicationBuilder($builder))();

        // loading definitions (can override everything else)
        (new DefinitionConfigLoader($builder))($projectDir, $appEnv);

        return $builder->build();
    }

    private function configureBuilder(string $projectDir, string $appEnv): ContainerBuilder
    {
        $builder = (new ContainerBuilder())
            ->useAttributes(true)
            ->enableCompilation($projectDir . self::CACHE_PATH . DIRECTORY_SEPARATOR . $appEnv);
        //apcu cache for definitions
        if ($this->apcuEnabled($appEnv)) {
            $builder->enableDefinitionCache($projectDir . $appEnv);
        }
        return $builder;
    }

    private function removeContainer(string $projectDir, string $appEnv): void
    {
        array_map('unlink', glob($projectDir . self::CACHE_PATH . DIRECTORY_SEPARATOR . $appEnv . DIRECTORY_SEPARATOR . self::COMPILED_FILENAME));
        $this->apcuEnabled($appEnv) && apcu_clear_cache();
    }

    private function apcuEnabled(string $appEnv): bool
    {
        return $appEnv == Kernel::ENV_PROD && function_exists('apcu_enabled') && apcu_enabled();
    }
}