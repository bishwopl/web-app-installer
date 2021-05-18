<?php

namespace WebAppInstaller\Contracts;

interface InstallParametersProviderInterface {

    /**
     * Structure of the parameters.
     * <code>
     * <ol>
     *   <li>If parameter is array then elements should be declared as child parameter.</li>
     *   <li>Filter and validator configuration is similar to lminas-form filter and validator configuration</li>
     * </ol>
     * </code>
     * @return array
     */
    public function getInstallParameters(): array;
    
}
