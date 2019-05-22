<?php
/**
 * @package    Packlink
 * @author     Packlink Shipping S.L.
 * @copyright  2019 Packlink
 */

namespace Packlink\Script;

use Composer\Script\Event;

class Core
{
    public static function postComposer(Event $event)
    {
        self::fixAndCopyDirectory('src', 'IntegrationCore');
        self::fixAndCopyDirectory('tests', 'IntegrationCore/Tests');
        self::copyResources();
    }

    private static function fixAndCopyDirectory($from, $to)
    {
        self::copyDirectory(__DIR__ . '/../vendor/packlink/integration-core/' . $from, __DIR__ . '/../tmp');
        self::renameNamespaces(__DIR__ . '/../tmp');
        self::copyDirectory(__DIR__ . '/../tmp', __DIR__ . '/../Packlink/PacklinkPro/' . $to);
        self::removeDirectory(__DIR__ . '/../tmp');
    }

    private static function copyDirectory($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ($file = readdir($dir))) {
            if (($file !== '.') && ( $file !== '..')) {
                if (is_dir($src . '/' . $file)) {
                    self::copyDirectory($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }

        closedir($dir);
    }

    private static function renameNamespaces($directory)
    {
        $iterator = new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::CHILD_FIRST);

        foreach($files as $file) {
            if (!$file->isDir()){
                $fileToChange = file_get_contents($file->getRealPath());
                $fileToChange = str_replace(
                    "Packlink\\",
                    "Packlink\\PacklinkPro\\IntegrationCore\\",
                    $fileToChange
                );
                file_put_contents(
                    $file->getRealPath(),
                    str_replace(
                        "Logeecom\\",
                        "Packlink\\PacklinkPro\\IntegrationCore\\",
                        $fileToChange
                    )
                );
            }
        }
    }

    private static function removeDirectory($directory)
    {
        $iterator = new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::CHILD_FIRST);

        foreach($files as $file) {
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        rmdir($directory);
    }

    /**
     * Copies resource files to module resources directory.
     */
    private static function copyResources()
    {
        self::copyDirectory(
            __DIR__ . '/../vendor/packlink/integration-core/src/BusinessLogic/Resources/js',
            __DIR__ . '/../Packlink/PacklinkPro/view/adminhtml/web/js/core'
        );
        self::copyDirectory(
            __DIR__ . '/../vendor/packlink/integration-core/src/BusinessLogic/Resources/js',
            __DIR__ . '/../Packlink/PacklinkPro/view/frontend/web/js/core'
        );
        self::copyDirectory(
            __DIR__ . '/../vendor/packlink/integration-core/src/BusinessLogic/Resources/LocationPicker',
            __DIR__ . '/../Packlink/PacklinkPro/view/frontend/web/location'
        );
        self::copyDirectory(
            __DIR__ . '/../vendor/packlink/integration-core/src/BusinessLogic/Resources/img/carriers',
            __DIR__ . '/../Packlink/PacklinkPro/view/adminhtml/web/images/carriers'
        );
        self::copyDirectory(
            __DIR__ . '/../vendor/packlink/integration-core/src/BusinessLogic/Resources/img/carriers',
            __DIR__ . '/../Packlink/PacklinkPro/view/frontend/web/images/carriers'
        );
    }
}
