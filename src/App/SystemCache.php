<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use DI\Attribute\Inject;
use Kuick\Cache\ApcuCache;
use Kuick\Cache\FilesystemCache;
use Kuick\Cache\InMemoryCache;
use Kuick\Cache\LayeredCache;
use Kuick\Cache\NullCache;

class SystemCache extends LayeredCache implements SystemCacheInterface
{
    public function __construct(
        #[Inject('kuick.app.projectDir')] string $projetcDir,
        #[Inject('kuick.app.env')] string $env,
    )
    {
        if ($env !== KernelInterface::ENV_PROD) {
            parent::__construct([new NullCache()]);
            return;
        }
        $cacheStack = [
            new InMemoryCache(),
        ];
        if ($this->isApcuAvailable()) {
            $cacheStack[] = new ApcuCache();
        }
        $cacheStack[] = new FilesystemCache($projetcDir . self::CACHE_PATH);
        parent::__construct($cacheStack);
    }

    private function isApcuAvailable(): bool
    {
        return file_exists('apcu_enabled') && apcu_enabled(); 
    }
}
