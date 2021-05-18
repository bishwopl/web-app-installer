<?php

namespace WebAppInstaller\Entity;

use WebAppInstaller\Entity\Module;
use Psr\Container\ContainerInterface;
use Doctrine\Common\Collections\ArrayCollection;

class AppInstaller {

    /**
     * @var \Psr\Container\ContainerInterface 
     */
    protected $container;

    /**
     * @var array
     */
    protected $installerClassNames;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $installParameterProviders;
    
    /**
     * @var int 
     */
    protected $currentModuleIndex = 0;

    /**
     * @param \Psr\Container\ContainerInterface $container
     * @param array $installerClassNames
     */
    public function __construct(ContainerInterface $container, array $installerClassNames) {
        $this->container = $container;
        $this->installerClassNames = array_values($installerClassNames);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function prepare(): void {
        $temp = [];
        foreach ($this->installerClassNames as $c) {
            try {
                if ($this->container->has($c)) {
                    $temp[] = $this->container->get($c);
                } else {
                    $temp[] = new $c();
                }
            } catch (Exception $ex) {
                throw new \Exception('Cannot initialize ' . $c . '.Reason : '.$ex->getMessage());
            }
        }
        usort($temp, function(Module $a, Module $b) {
            if($a->getInstallPriority() == $b->getInstallPriority()){
                return 0;
            }
            return $a->getInstallPriority() < $b->getInstallPriority()?-1:1;
        });
        $this->installParameterProviders = new ArrayCollection($temp);
    }
    
}
