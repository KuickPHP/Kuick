<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework;

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
    ) {
        // in non-prod env we use NullCache only
        if ($env !== Kernel::ENV_PROD) {
            parent::__construct([new NullCache()]);
            return;
        }
        // cache stack for prod env
        $prodCacheStack = [
            new InMemoryCache(),
        ];
        // if apcu cache is available - we use it
        if (function_exists('apcu_enabled') && apcu_enabled()) {
            $prodCacheStack[] = new ApcuCache();
        }
        // filesystem cache is always used
        $prodCacheStack[] = new FilesystemCache($projetcDir . self::CACHE_PATH);
        parent::__construct($prodCacheStack);
    }
}
