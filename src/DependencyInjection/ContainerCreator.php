<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\DependencyInjection;

use DI\ContainerBuilder;
use Kuick\Framework\KernelInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class ContainerCreator
{
    private const CACHE_PATH =  '/var/cache';
    private const COMPILED_FILENAME = 'CompiledContainer.php';

    private array $loadedDefinitions = [];

    public function create(string $projectDir): ContainerInterface
    {
        // setting the default env if not set
        if (false === getenv(KernelInterface::APP_ENV)) {
            putenv(KernelInterface::APP_ENV . '=' . KernelInterface::ENV_PROD);
        }
        $appEnv = getenv(KernelInterface::APP_ENV);

        // build or load from cache
        $container = $this->configureBuilder($projectDir, $appEnv)->build();

        // check if cached container is ready
        if ($container->has(KernelInterface::DI_PROJECT_DIR_KEY)) {
            $container->get(LoggerInterface::class)->info('Running in "' . $appEnv . '" mode, DI container loaded from cache');
            return $container;
        }

        $container = $this->rebuildContainer($projectDir, $appEnv);
        $logger = $container->get(LoggerInterface::class);
        $logger->warning('Running in "' . $appEnv . '" mode, DI container rebuilt');
        // log loaded definitions
        foreach ($this->loadedDefinitions as $definition) {
            $logger->debug('Adding DI definitions: ' . $definition);
        }
        return $container;
    }

    private function rebuildContainer(string $projectDir, string $appEnv): ContainerInterface
    {
        // removing previous container if exists
        $this->removeContainer($projectDir, $appEnv);

        // creating new builder
        $builder = $this->configureBuilder($projectDir, $appEnv);

        // adding default definitions
        $builder->addDefinitions([
            KernelInterface::DI_APP_ENV_KEY => $appEnv,
            KernelInterface::DI_PROJECT_DIR_KEY => $projectDir,
        ]);

        // loading definitions (can override everything else)
        $this->loadedDefinitions = (new DefinitionConfigLoader())->load($builder, $projectDir, $appEnv);

        return $builder->build();
    }

    private function configureBuilder(string $projectDir, string $appEnv): ContainerBuilder
    {
        $builder = (new ContainerBuilder())->useAttributes(true);
        // enable compilation for production
        if (($appEnv !== KernelInterface::ENV_DEV)) {
            $builder->enableCompilation($projectDir . self::CACHE_PATH . DIRECTORY_SEPARATOR . $appEnv);
            // optional apcu definition cache
            $this->isApcuAvailable() && $builder->enableDefinitionCache($projectDir . $appEnv);
        }
        return $builder;
    }

    private function removeContainer(string $projectDir, string $appEnv): void
    {
        array_map('unlink', glob($projectDir . self::CACHE_PATH . DIRECTORY_SEPARATOR . $appEnv . DIRECTORY_SEPARATOR . self::COMPILED_FILENAME));
        // clear potential apcu cache
        if (($appEnv !== KernelInterface::ENV_DEV) && $this->isApcuAvailable()) {
            apcu_clear_cache();
        }
    }

    private function isApcuAvailable(): bool
    {
        return function_exists('apcu_enabled') && apcu_enabled();
    }
}
