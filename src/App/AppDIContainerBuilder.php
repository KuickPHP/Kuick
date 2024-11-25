<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use DI\ContainerBuilder;
use Kuick\App\Services\BuildActionMatcher;
use Kuick\App\Services\BuildConfiguration;
use Kuick\App\Services\BuildConsoleApplication;
use Kuick\App\Services\BuildLogger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 *
 */
class AppDIContainerBuilder
{
    private const CACHE_PATH = BASE_PATH . '/var/tmp';
    private const COMPILED_FILENAME = 'CompiledContainer.php';
    private const APP_ENV_KEY = 'KUICK_APP_ENV';
    private const APP_ENV_CONFIGURATION_KEY = 'kuick.app.env';
    private const READY_DEFINITION = 'kuick.app.name';

    private string $appEnv;

    public function __invoke(): ContainerInterface
    {
        //determining kuick.app.env (ie. dev, prod)
        $this->appEnv = $this->determineEnv();

        //remove previous compilation if KUICK_APP_ENV!=dev
        if ($this->appEnv == KernelAbstract::ENV_DEV) {
            $this->removeContainer();
        }

        //build or load from cache
        $container = $this->configureBuilder()->build();

        //validating if container is built
        if ($container->has(self::READY_DEFINITION)) {
            $logger = $container->get(LoggerInterface::class);
            $logger->info('Application is running in ' . $this->appEnv . ' mode');
            $logger->info('DI container loaded from cache');
            return $container;
        }

        //rebuilding if validation failed
        $container = $this->rebuildContainer();
        $logger = $container->get(LoggerInterface::class);
        $logger->log(
            $this->appEnv == KernelAbstract::ENV_DEV ? LogLevel::WARNING : LogLevel::INFO,
            'Application is running in ' . $this->appEnv . ' mode'
        );
        $logger->notice('DI container rebuilt');
        return $container;
    }

    private function rebuildContainer(): ContainerInterface
    {
        $this->removeContainer();
        $builder = $this->configureBuilder();

        //DI definitions (configuration)
        (new BuildConfiguration($builder))($this->appEnv);

        //load environment variables
        $builder->addDefinitions((new AppGetEnvironment())());

        //logger
        (new BuildLogger($builder))();

        //action matcher
        (new BuildActionMatcher($builder))();

        //console application
        (new BuildConsoleApplication($builder))();

        return $builder->build();
    }

    private function configureBuilder(): ContainerBuilder
    {
        $builder = (new ContainerBuilder())
            ->useAutowiring(true)
            ->useAttributes(true)
            ->enableCompilation(self::CACHE_PATH . DIRECTORY_SEPARATOR . $this->appEnv);
        if ($this->isApcuEnabled()) {
            $builder->enableDefinitionCache(__DIR__);
        }
        return $builder;
    }

    private function removeContainer(): void
    {
        /** @disregard P1009 Undefined type */
        $this->isApcuEnabled() && apcu_clear_cache();
        array_map('unlink', glob(self::CACHE_PATH . DIRECTORY_SEPARATOR . $this->appEnv . DIRECTORY_SEPARATOR . self::COMPILED_FILENAME));
    }

    private function isApcuEnabled(): bool
    {
        /** @disregard P1009 Undefined type */
        return function_exists('apcu_enabled') && apcu_enabled();
    }

    private function determineEnv(): string
    {
        $envVarFromSystemEnv = getenv(self::APP_ENV_KEY);
        //best performance - put KUICK_APP_ENV directly into system environment variables
        if ($envVarFromSystemEnv) {
            print_r($envVarFromSystemEnv);
            return $envVarFromSystemEnv;
        }
        $envVariablesFromDotEnv = (new AppGetEnvironment())();
        return $envVariablesFromDotEnv[self::APP_ENV_CONFIGURATION_KEY] ?? KernelAbstract::ENV_PROD;
    }
}
