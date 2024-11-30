<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\DIFactories;

use Throwable;

class ArrayToFile
{
    public static function load(string $fileName): ?array
    {
        try {
            return include($fileName);
        } catch (Throwable $error) {
            unset($error); //do nothing
        }
        return null;
    }

    public static function save(string $fileName, array $object): void
    {
        file_put_contents($fileName, "<?php\n" . var_export($object, true) . ';');
    }
}
