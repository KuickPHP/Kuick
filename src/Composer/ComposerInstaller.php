<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Composer;

use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;

/**
 *
 */
class ComposerInstaller
{
    private const KUICK_PATH =  '/vendor/kuick/framework';
    private const KUICK_COMPONENTS_PATTERN = '/vendor/kuick/*/';
    private const INDEX_FILE = '/public/index.php';
    private const CONSOLE_FILE = '/bin/console';
    private const SOURCE_ETC_DIR = '/etc';
    private const TARGET_ETC_DIR = '/etc';
    private const TMP_DIR = '/var/cache';

    /** @disregard P1009 Undefined type */
    protected static function initAutoload(Event $event): void
    {
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        define('BASE_PATH', realpath(dirname($vendorDir)));
        self::createTmpDir();
        require $vendorDir . '/autoload.php';
    }

    /** @disregard P1009 Undefined type */
    public static function postInstall(Event $event): void
    {
        self::initAutoload($event);
        self::copyDistributionFiles();
    }

    /** @disregard P1009 Undefined type */
    public static function postUpdate(Event $event): void
    {
        self::postInstall($event);
    }

    protected static function createTmpDir(): void
    {
        $fs = new Filesystem();
        $tmpPath = BASE_PATH . self::TMP_DIR;
        if (!$fs->exists($tmpPath)) {
            $fs->mkdir($tmpPath);
        }
        $fs->chmod($tmpPath, 0777, 0000, true);
    }

    protected static function copyDistributionFiles(): void
    {
        $fs = new Filesystem();
        $kuickVendorPath = BASE_PATH . self::KUICK_PATH;
        $kuickVendorComponentsPath = BASE_PATH . self::KUICK_COMPONENTS_PATTERN;
        $indexPHPFile = BASE_PATH . self::INDEX_FILE;
        $consoleFile = BASE_PATH . self::CONSOLE_FILE;
        //public/index.php and bin/console exists - nothing else to do
        if ($fs->exists($indexPHPFile) && file_exists($consoleFile)) {
            return;
        }
        //vendor dir not preset
        if (!$fs->exists($kuickVendorPath)) {
            return;
        }
        $fs->copy($kuickVendorPath . self::INDEX_FILE, $indexPHPFile);
        $fs->copy($kuickVendorPath . self::CONSOLE_FILE, $consoleFile);
        $fs->chmod($consoleFile, 0755);
        $fs->mirror($kuickVendorPath . self::SOURCE_ETC_DIR, BASE_PATH . self::TARGET_ETC_DIR);
        foreach (glob($kuickVendorComponentsPath . self::SOURCE_ETC_DIR) as $vendorEtcs) {
            $fs->mirror($vendorEtcs, BASE_PATH . self::TARGET_ETC_DIR);
        }
    }
}
