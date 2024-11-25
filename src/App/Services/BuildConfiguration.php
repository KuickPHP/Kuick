<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Services;

/**
 *
 */
class BuildConfiguration extends ServiceBuildAbstract
{
    private const DEFINITION_LOCATIONS = [
        BASE_PATH . '/vendor/kuick/*/etc/di/*.di.php',
        BASE_PATH . '/etc/di/*.di.php',
    ];
    private const ENV_SPECIFIC_DEFINITION_LOCATIONS_TEMPLATE = BASE_PATH . '/etc/di/*.di@%s.php';

    public function __invoke(string $env): void
    {
        //adding global definition files
        foreach (self::DEFINITION_LOCATIONS as $definitionsLocation) {
            foreach (glob($definitionsLocation) as $definitionFile) {
                $this->builder->addDefinitions($definitionFile);
            }
        }
        //adding env specific definition files
        foreach (glob(sprintf(self::ENV_SPECIFIC_DEFINITION_LOCATIONS_TEMPLATE, $env)) as $definitionFile) {
            $this->builder->addDefinitions($definitionFile);
        }
    }
}
