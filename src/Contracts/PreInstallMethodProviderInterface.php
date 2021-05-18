<?php

namespace WebAppInstaller\Contracts;

interface PreInstallMethodProviderInterface {

    /**
     * @return \WebAppInstaller\Contracts\ExecutionResultInterface
     */
    public function executePreInstallScript(): ExecutionResultInterface;
}
