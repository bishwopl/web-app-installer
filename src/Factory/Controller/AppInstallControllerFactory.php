<?php

namespace WebAppInstaller\Factory\Controller;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use WebAppInstaller\Controller\AppInstallController;

class AppInstallControllerFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return AppInstallController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new AppInstallController(
                $container->get(\WebAppInstaller\Service\InstallerService::class)
        );
    }

}
