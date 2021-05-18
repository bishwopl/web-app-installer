<?php

namespace WebAppInstaller\Factory\Service;

use WebAppInstaller\Service\InstallerService;
use WebAppInstaller\Entity\Module;
use WebAppInstaller\Service\ConfigSaverService;
use WebAppInstaller\Service\ProgressSaverService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Doctrine\Common\Collections\ArrayCollection;

class InstallerServiceFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return InstallerService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $moduleOptions = $container->get(\WebAppInstaller\Options\Options::class);
        $hooks = $moduleOptions->getInstallHooks();
        $temp = [];

        foreach ($hooks as $h) {
            try {
                $temp[] = $container->has($h) ? new Module($container->get($h)) : new Module(new $h());
            } catch (Exception $ex) {
                throw new \Exception('Cannot initialize ' . $h . '.Reason : ' . $ex->getMessage());
            }
        }

        usort($temp, function(Module $a, Module $b) {
            return $a->getInstallPriority() == $b->getInstallPriority() ? 0 : ($a->getInstallPriority() < $b->getInstallPriority() ? -1 : 1);
        });

        if(!is_dir($moduleOptions->getDataDirectory())){
            mkdir($moduleOptions->getDataDirectory());
        }
        
        return new InstallerService(
                $moduleOptions,
                $container->get(ConfigSaverService::class),
                new ArrayCollection($temp),
                new ProgressSaverService($moduleOptions->getDataDirectory())
        );
    }

}
