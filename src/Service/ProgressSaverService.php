<?php

namespace WebAppInstaller\Service;

use Doctrine\Common\Cache\PhpFileCache;

class ProgressSaverService extends PhpFileCache {

    /**
     * @var string
     */
    protected $dir;

    public function __construct(string $dir) {
        $this->dir = $dir;
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        parent::__construct($dir, '.install-progress.php');
    }
}
