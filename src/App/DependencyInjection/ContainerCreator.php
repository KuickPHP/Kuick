<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\DependencyInjection;

use APCUIterator;
use DI\ContainerBuilder;
use Kuick\App\Kernel;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 *
 */
class ContainerCreator
{
    private const CACHE_PATH =  '/var/cache';
    private const COMPILED_FILENAME = 'CompiledContainer.php';
    private string $appEnv;

    public function __invoke(string $projectDir): ContainerInterface
    {
        //setting default env
        (false === getenv(Kernel::APP_ENV)) && putenv(Kernel::APP_ENV . '=' . Kernel::ENV_PROD);
        $this->appEnv = getenv(Kernel::APP_ENV);

        //remove container if not in production mode
        $this->appEnv !== Kernel::ENV_PROD && $this->removeContainer($projectDir);

        //build or load from cache
        $container = $this->configureBuilder($projectDir)->build();

        //for production mode, check if container is already built and return it if so
        if ($container->has(Kernel::DI_PROJECT_DIR_KEY)) {
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
            $this->appEnv == Kernel::ENV_DEV ? LogLevel::WARNING : LogLevel::INFO,
            'Application is running in ' . $this->appEnv . ' mode'
        );
        return $container;
    }

    private function rebuildContainer(string $projectDir): ContainerInterface
    {
        $builder = $this->configureBuilder($projectDir);

        $builder->addDefinitions([
            Kernel::DI_APP_ENV_KEY => $this->appEnv,
            Kernel::DI_PROJECT_DIR_KEY => $projectDir,
        ]);

        //loading DI definitions (configuration)
        (new DefinitionConfigLoader($builder))($projectDir, $this->appEnv);

        (new RequestHandlerBuilder($builder))();

        //logger builder
        (new LoggerBuilder($builder))();

        //event dispatcher
        (new EventDispatcherBuilder($builder))();

        //action matcher
        (new RouterBuilder($builder))();

        return $builder->build();
    }

    private function configureBuilder(string $projectDir): ContainerBuilder
    {
        $builder = (new ContainerBuilder())
            ->useAttributes(true)
            ->enableCompilation($projectDir . self::CACHE_PATH . DIRECTORY_SEPARATOR . $this->appEnv);
        //apcu cache for definitions
        if ($this->appEnv == Kernel::ENV_PROD && $this->apcuEnabled()) {
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
