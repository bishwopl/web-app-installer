<?php

namespace WebAppInstaller\Service;

class ExtensionCheckService {

    /**
     * Returns loaded extension. Replaces 'php_' from ext name
     * @return array
     */
    private static function getLoadedExtensions(): array {
        $exts = array_merge(get_loaded_extensions(), get_loaded_extensions(true));
        return array_map(function($ext) {
            return str_replace('php_', '', strtolower($ext));
        }, $exts);
    }

    public static function isExtensionLoaded($ext): bool {
        $loadedExts = self::getLoadedExtensions();
        return in_array(str_replace('php_', '', strtolower($ext)), $loadedExts);
    }

}
