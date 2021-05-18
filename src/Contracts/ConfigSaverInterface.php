<?php

namespace WebAppInstaller\Contracts;

interface ConfigSaverInterface {

    /**
     * Save configuration
     * @param array $config
     */
    public function save(array $config) : ExecutionResultInterface;
}
