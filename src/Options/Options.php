<?php

namespace WebAppInstaller\Options;

use Laminas\Stdlib\AbstractOptions;

class Options extends AbstractOptions {

    /**
     * @var array
     */
    protected $installHooks;

    /**
     * @var string
     */
    protected $defaultConfigDirectory;

    /**
     * @var string
     */
    protected $defaultConfigFileName;
    
    /**
     * @var string 
     */
    protected $dataDirectory;

    public function getInstallHooks(): array {
        return $this->installHooks;
    }

    public function getDefaultConfigDirectory(): string {
        return $this->defaultConfigDirectory;
    }

    public function getDefaultConfigFileName(): string {
        return $this->defaultConfigFileName;
    }

    public function setInstallHooks(array $installHooks): Options {
        $this->installHooks = $installHooks;
        return $this;
    }

    public function setDefaultConfigDirectory(string $defaultConfigDirectory): Options {
        $this->defaultConfigDirectory = $defaultConfigDirectory;
        return $this;
    }

    public function setDefaultConfigFileName(string $defaultConfigFileName): Options {
        $this->defaultConfigFileName = $defaultConfigFileName;
        return $this;
    }

    public function getDataDirectory(): string {
        return $this->dataDirectory;
    }

    public function setDataDirectory(string $dataDirectory) {
        $this->dataDirectory = $dataDirectory;
        return $this;
    }
}
