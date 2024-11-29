<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Services;

use Kuick\App\AppDIContainerBuilder;
use Kuick\App\KernelAbstract;
use Throwable;

class CacheWrapper
{
    public static function load(string $env, string $className): mixed
    {
        //bypass cache non prod environment
        if (KernelAbstract::ENV_PROD != $env) {
            return null;
        }
        $cacheFile = AppDIContainerBuilder::CACHE_PATH . DIRECTORY_SEPARATOR . $env . DIRECTORY_SEPARATOR . urlencode($className) . '.php';
        try {
            $serializedObject = file_get_contents($cacheFile);
            return unserialize($serializedObject);
        } catch (Throwable $error) {
            unset($error); //do nothing
        }
        return null;
    }

    public static function save(string $env, string $className, mixed $object): void
    {
        $cacheFile = AppDIContainerBuilder::CACHE_PATH . DIRECTORY_SEPARATOR . $env . DIRECTORY_SEPARATOR . urlencode($className) . '.php';
        file_put_contents($cacheFile, serialize($object));
    }
}
