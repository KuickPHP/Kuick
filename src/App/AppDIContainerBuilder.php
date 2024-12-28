<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use APCUIterator;
use DI\ContainerBuilder;
use Kuick\App\DIFactories\BuildLogger;
use Kuick\App\DIFactories\AddDefinitions;
use Kuick\App\DIFactories\BuildRouter;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 *
 */
class AppDIContainerBuilder
{
    public const APP_ENV_CONFIGURATION_KEY = 'kuick.app.env';
    public const PROJECT_DIR_CONFIGURATION_KEY = 'kuick.app.project.dir';
    public const CACHE_PATH =  '/var/cache';

    private string $appEnv;

    public function __invoke(string $projectDir): ContainerInterface
    {
        //parse and load .env files
        new DotEnvLoader($projectDir);

        (false === getenv(KernelAbstract::APP_ENV)) && putenv(KernelAbstract::APP_ENV . '=' . KernelAbstract::ENV_PROD);
        $this->appEnv = getenv(KernelAbstract::APP_ENV);

        //build or load from cache
        $container = $this->configureBuilder($projectDir)->build();

        //validating if container is built
        if ($container->has(self::PROJECT_DIR_CONFIGURATION_KEY)) {
            $logger = $container->get(LoggerInterface::class);
            $logger->info('Application is running in ' . $this->appEnv . ' mode');
            $logger->debug('DI container loaded from cache');
            return $container;
        }

        //rebuilding if validation failed
        $container = $this->rebuildContainer($projectDir);
        $logger = $container->get(LoggerInterface::class);
        $logger->notice('DI container rebuilt, cache written');
        $logger->log(
            $this->appEnv == KernelAbstract::ENV_DEV ? LogLevel::WARNING : LogLevel::INFO,
            'Application is running in ' . $this->appEnv . ' mode'
        );
        return $container;
    }

    private function rebuildContainer(string $projectDir): ContainerInterface
    {
        $builder = $this->configureBuilder($projectDir);

        $builder->addDefinitions([
            self::APP_ENV_CONFIGURATION_KEY => $this->appEnv,
            self::PROJECT_DIR_CONFIGURATION_KEY => $projectDir,
        ]);

        //loading DI definitions (configuration)
        (new AddDefinitions($builder))($projectDir, $this->appEnv);

        //logger
        (new BuildLogger($builder))();

        //action matcher
        (new BuildRouter($builder))();

        return $builder->build();
    }

    private function configureBuilder(string $projectDir): ContainerBuilder
    {
        $builder = (new ContainerBuilder())
            ->useAutowiring(true)
            ->useAttributes(true);
        if (KernelAbstract::ENV_PROD === $this->appEnv) {
            $builder->enableCompilation($projectDir . self::CACHE_PATH . DIRECTORY_SEPARATOR . $this->appEnv);
            function_exists('apcu_enabled') && apcu_enabled() && $builder->enableDefinitionCache($projectDir . $this->appEnv);
        }
        return $builder;
    }
}
