<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use DI\ContainerBuilder;
use Kuick\App\DIFactories\BuildLogger;
use Kuick\App\DIFactories\BuildRouteMatcher;
use Kuick\App\DIFactories\LoadDefinitions;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 *
 */
class AppDIContainerBuilder
{
    public const PROJECT_DIR_CONFIGURATION_KEY = 'app.project.dir';
    public const CACHE_PATH =  '/var/cache';

    private const COMPILED_FILENAME = 'CompiledContainer.php';
    private const APP_ENV_KEY = 'KUICK_APP_ENV';
    private const APP_ENV_CONFIGURATION_KEY = 'kuick.app.env';
    private const READY_DEFINITION = 'kuick.app.name';

    private string $appEnv;

    public function __invoke(string $projectDir): ContainerInterface
    {
        //determining kuick.app.env (ie. dev, prod)
        $this->appEnv = $this->determineEnv($projectDir);

        //remove previous compilation if KUICK_APP_ENV!=dev
        if ($this->appEnv == KernelAbstract::ENV_DEV) {
            $this->removeContainer($projectDir);
        }

        //build or load from cache
        $container = $this->configureBuilder($projectDir)->build();

        //validating if container is built
        if ($container->has(self::READY_DEFINITION)) {
            $logger = $container->get(LoggerInterface::class);
            $logger->info('Application is running in ' . $this->appEnv . ' mode');
            $logger->info('DI container loaded from cache');
            return $container;
        }

        //rebuilding if validation failed
        $container = $this->rebuildContainer($projectDir);
        $logger = $container->get(LoggerInterface::class);
        $logger->log(
            $this->appEnv == KernelAbstract::ENV_DEV ? LogLevel::WARNING : LogLevel::INFO,
            'Application is running in ' . $this->appEnv . ' mode'
        );
        $logger->notice('DI container rebuilt, cache written');
        return $container;
    }

    private function rebuildContainer(string $projectDir): ContainerInterface
    {
        $this->removeContainer($projectDir);
        $builder = $this->configureBuilder($projectDir);

        $builder->addDefinitions([self::PROJECT_DIR_CONFIGURATION_KEY => $projectDir]);

        //loading DI definitions (configuration)
        (new LoadDefinitions($builder))($projectDir, $this->appEnv);

        //adding environment configuration
        $builder->addDefinitions((new AppGetEnvironment())($projectDir));

        //logger
        (new BuildLogger($builder))();

        //action matcher
        (new BuildRouteMatcher($builder))();

        return $builder->build();
    }

    private function configureBuilder(string $projectDir): ContainerBuilder
    {
        $builder = (new ContainerBuilder())
            ->useAutowiring(true)
            ->useAttributes(true)
            ->enableCompilation($projectDir . self::CACHE_PATH . DIRECTORY_SEPARATOR . $this->appEnv);
        return $builder;
    }

    private function removeContainer(string $projectDir): void
    {
        array_map('unlink', glob($projectDir . self::CACHE_PATH . DIRECTORY_SEPARATOR . $this->appEnv . DIRECTORY_SEPARATOR . self::COMPILED_FILENAME));
    }

    private function determineEnv(string $projectDir): string
    {
        //best performance - KUICK_APP_ENV found directly in the system environment variables
        $envVarFromSystemEnv = getenv(self::APP_ENV_KEY);
        if ($envVarFromSystemEnv) {
            return $envVarFromSystemEnv;
        }
        //checking out .env files
        $envVariablesFromDotEnv = (new AppGetEnvironment())($projectDir);
        return $envVariablesFromDotEnv[self::APP_ENV_CONFIGURATION_KEY] ?? KernelAbstract::ENV_PROD;
    }
}
