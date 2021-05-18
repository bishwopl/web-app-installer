<?php

namespace WebAppInstaller\Contracts;

interface PostInstallMethodProviderInterface {

    /**
     * @return \WebAppInstaller\Contracts\ExecutionResultInterface
     */
    public function executePostInstallScript(): ExecutionResultInterface;
}
