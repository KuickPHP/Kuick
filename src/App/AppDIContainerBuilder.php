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
use Kuick\App\DIFactories\BuildEventDispatcher;
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
    public const PROJECT_DIR_CONFIGURATION_KEY = 'kuick.app.projectDir';
    public const CACHE_PATH =  '/var/cache';

    private const COMPILED_FILENAME = 'CompiledContainer.php';

    private string $appEnv;

    public function __invoke(string $projectDir): ContainerInterface
    {
        //parse and load .env files
        new DotEnvLoader($projectDir);

        //setting default env
        (false === getenv(KernelInterface::APP_ENV)) && putenv(KernelInterface::APP_ENV . '=' . KernelInterface::ENV_PROD);
        $this->appEnv = getenv(KernelInterface::APP_ENV);

        //remove container if not in production mode
        $this->appEnv !== KernelInterface::ENV_PROD && $this->removeContainer($projectDir);

        //build or load from cache
        $container = $this->configureBuilder($projectDir)->build();

        //for production mode, check if container is already built and return it if so
        if ($container->has(self::PROJECT_DIR_CONFIGURATION_KEY)) {
            $logger = $container->get(LoggerInterface::class);
            $logger->info('Application is running in ' . $this->appEnv . ' mode');
            $logger->debug('DI container loaded from cache');
            return $container;
        }
        //rebuilding if validation failed
        $this->removeContainer($projectDir);
        $container = $this->rebuildContainer($projectDir);
        $logger = $container->get(LoggerInterface::class);
        $logger->notice('DI container rebuilt, cache written');
        $logger->log(
            $this->appEnv == KernelInterface::ENV_DEV ? LogLevel::WARNING : LogLevel::INFO,
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

        //event dispatcher
        (new BuildEventDispatcher($builder))();

        //action matcher
        (new BuildRouter($builder))();

        return $builder->build();
    }

    private function configureBuilder(string $projectDir): ContainerBuilder
    {
        $builder = (new ContainerBuilder())
            ->useAutowiring(true)
            ->useAttributes(true)
            ->enableCompilation($projectDir . self::CACHE_PATH . DIRECTORY_SEPARATOR . $this->appEnv);
        //apcu cache for definitions
        if ($this->appEnv == KernelInterface::ENV_PROD && $this->apcuEnabled()) {
            $builder->enableDefinitionCache($projectDir . $this->appEnv);
        }
        return $builder;
    }

    private function removeContainer(string $projectDir): void
    {
        array_map('unlink', glob($projectDir . self::CACHE_PATH . DIRECTORY_SEPARATOR . $this->appEnv . DIRECTORY_SEPARATOR . self::COMPILED_FILENAME));
        if ($this->apcuEnabled()) {
            $apcuIterator = new APCUIterator('$php-di.definitions.' . $projectDir . $this->appEnv . '$');
            //DI definition apcu cache cleanup
            foreach ($apcuIterator as $key) {
                apcu_delete($key['key']);
            }
        }
    }

    private function apcuEnabled(): bool
    {
        return function_exists('apcu_enabled') && apcu_enabled();
    }
}
