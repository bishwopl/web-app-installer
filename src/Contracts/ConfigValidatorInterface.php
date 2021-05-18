<?php

namespace WebAppInstaller\Contracts;

interface ConfigValidatorInterface {

    /**
     * @param array $config
     * @return bool
     */
    public function validateConfig(array $config): ExecutionResultInterface;
}
