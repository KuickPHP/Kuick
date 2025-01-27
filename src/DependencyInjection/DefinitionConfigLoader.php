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

/**
 * DI Definition Loader
 */
class DefinitionConfigLoader
{
    private const CONFIG_LOCATION_TEMPLATES = [
        '/vendor/kuick/*/config/di/*.di.php',
        '/config/di/*.di.php',
    ];
    private const ENV_SPECIFIC_DEFINITION_LOCATION_TEMPLATE = '/config/di/*.di@%s.php';

    public function __construct(private ContainerBuilder $builder)
    {
    }

    public function __invoke(string $projectDir, string $env): array
    {
        $loadedDefinitions = [];
        // iterating over all possible locations
        foreach (self::CONFIG_LOCATION_TEMPLATES as $configurationTemplate) {
            // adding definition files in the current location
            foreach (glob($projectDir . $configurationTemplate) as $definitionFile) {
                $this->builder->addDefinitions($definitionFile);
                $loadedDefinitions[] = $definitionFile;
            }
        }
        //adding env specific definition files
        foreach (glob(sprintf($projectDir . self::ENV_SPECIFIC_DEFINITION_LOCATION_TEMPLATE, $env)) as $definitionFile) {
            $this->builder->addDefinitions($definitionFile);
            $loadedDefinitions[] = $definitionFile;
        }
        return $loadedDefinitions;
    }
}
