<?php

namespace WebAppInstaller\Factory\Service;

use WebAppInstaller\Service\ConfigSaverService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ConfigSaverServiceFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return ConfigSaverService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $moduleOptions = $container->get(\WebAppInstaller\Options\Options::class);
        return new ConfigSaverService(
                $moduleOptions->getDefaultConfigDirectory().'/'.$moduleOptions->getDefaultConfigFileName()
        );
    }

}
