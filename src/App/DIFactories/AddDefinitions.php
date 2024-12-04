<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\DIFactories;

/**
 *
 */
class AddDefinitions extends FactoryAbstract
{
    private const DEFINITION_LOCATIONS = [
        '/vendor/kuick/*/etc/di/*.di.php',
        '/etc/di/*.di.php',
    ];
    private const ENV_SPECIFIC_DEFINITION_LOCATIONS_TEMPLATE = '/etc/di/*.di@%s.php';

    public function __invoke(string $projectDir, string $env): void
    {
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
