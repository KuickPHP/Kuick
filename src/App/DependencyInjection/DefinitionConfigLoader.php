<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\DependencyInjection;

use DI\ContainerBuilder;
use Kuick\App\Kernel;

/**
 *
 */
class DefinitionConfigLoader
{
    private const DEFINITION_LOCATIONS = [
         //@TODO: remove this (attach files to the distribution)
        '/vendor/kuick/*/config/di/*.di.php',
        '/config/di/*.di.php',
    ];
    private const ENV_SPECIFIC_DEFINITION_LOCATIONS_TEMPLATE = '/config/di/*.di@%s.php';

    public function __construct(private ContainerBuilder $builder)
    {
    }

    public function __invoke(string $projectDir, string $env): void
    {        
        $this->builder->addDefinitions([
            Kernel::DI_APP_ENV_KEY => $env,
            Kernel::DI_PROJECT_DIR_KEY => $projectDir,
            'kuick.app.name' => 'Kuick App',
            'kuick.app.charset' => 'UTF-8',
            'kuick.app.locale' => 'en_US.utf-8',
            'kuick.app.timezone' => 'UTC',
            'kuick.app.monolog.usemicroseconds' => false,
            'kuick.app.monolog.level' => 'INFO',
            'kuick.app.monolog.handlers' => [
                [
                    'type' => 'fingersCrossed',
                ],
            ],
            'kuick.ops.guard.token' => '',
        ]);
        //adding global definition files
        foreach (self::DEFINITION_LOCATIONS as $definitionsLocation) {
            foreach (glob($projectDir . $definitionsLocation) as $definitionFile) {
                $this->builder->addDefinitions($definitionFile);
            }
        }
        //adding env specific definition files
        foreach (glob(sprintf($projectDir . self::ENV_SPECIFIC_DEFINITION_LOCATIONS_TEMPLATE, $env)) as $definitionFile) {
            $this->builder->addDefinitions($definitionFile);
        }
    }
}
