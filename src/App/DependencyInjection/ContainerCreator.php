<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
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
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
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
        if (Kernel::ENV_DEV === $this->appEnv) {
            $this->removeContainer($projectDir);
        }

        //build or load from cache
        $container = $this->configureBuilder($projectDir)->build();

        //for production, return cached container if exists
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

        // building application services
        (new ServiceImplementationMapper($builder))();

        // loading definitions (can override the default service mappings)
        (new DefinitionConfigLoader($builder))($projectDir, $this->appEnv);

        // building request handler
        (new RequestHandlerBuilder($builder))();

        // creating logger
        (new LoggerBuilder($builder))();

        // creating event dispatcher
        (new EventDispatcherBuilder($builder))();

        // creating router matcher
        (new RouterBuilder($builder))();

        // creating guardhouse
        (new GuardhouseBuilder($builder))();

        //performance optimization (direct autowires)
        (new OptionalAutowires($builder))();

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
