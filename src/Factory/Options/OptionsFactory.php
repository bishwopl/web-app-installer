<?php

namespace WebAppInstaller\Factory\Options;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use WebAppInstaller\Options\Options;

class OptionsFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param type $requestedName
     * @param array $options
     * @return Options
     */
    public function __invoke(ContainerInterface $container, $requestedName, Array $options = null) {
        $config = $container->get('Config');
        return new Options(
            isset($config['web_app_installer']) ? $config['web_app_installer'] : ''
        );
    }

}