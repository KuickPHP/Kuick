<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use Symfony\Component\Console\Application;
use Throwable;

/**
 * Console Application Kernel
 */
final class ConsoleKernel extends KernelAbstract
{
    public function __invoke(): void
    {
        ini_set('max_execution_time', 0);
        try {
            $application = $this->container->get(Application::class);
            $application->run();
        } catch (Throwable $error) {
            $this->logger->error($error->getMessage());
            echo $error->getMessage() . PHP_EOL;
            exit(1);
        }
    }
}
