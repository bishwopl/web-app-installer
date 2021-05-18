<?php

namespace WebAppInstaller\Service;

class VersionCheckService {

    public static function checkVersion($requiredVersion): bool {
        return version_compare(PHP_VERSION, $requiredVersion) >= 0;
    }

}
