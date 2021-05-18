<?php

namespace WebAppInstaller;

use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\Filter\Callback;

class Module implements ConfigProviderInterface{

    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

}
