<?php

namespace WebAppInstaller\Service;

use WebAppInstaller\Options\Options;
use WebAppInstaller\Contracts\ConfigSaverInterface;
use WebAppInstaller\Contracts\ExecutionResultInterface;
use WebAppInstaller\Contracts\PreInstallMethodProviderInterface;
use WebAppInstaller\Contracts\PostInstallMethodProviderInterface;
use WebAppInstaller\Contracts\ConfigValidatorInterface;
use WebAppInstaller\Entity\Module;
use WebAppInstaller\Entity\ExecutionResult;
use WebAppInstaller\Service\VersionCheckService;
use WebAppInstaller\Service\ExtensionCheckService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Cache\PhpFileCache;

class InstallerService {

    const CHECKREQUIREMENTSTEP = 'checkRequirement';
    const PREINSTALLSTEP = 'preInstall';
    const ACCEPTCONFIGSTEP = 'acceptConfig';
    const POSTINSTALLSTEP = 'postInstall';
    const INSTALLATIONCOMPLETE = 'installationComplete';

    public static $STEPS = [
        self::CHECKREQUIREMENTSTEP,
        self::PREINSTALLSTEP,
        self::ACCEPTCONFIGSTEP,
        self::POSTINSTALLSTEP,
        self::INSTALLATIONCOMPLETE,
    ];

    /**
     * @var \Doctrine\Common\Cache\PhpFileCache
     */
    protected $progressStorage;

    /**
     * @var \WebAppInstaller\Options\Options 
     */
    protected $options;

    /**
     * @var \WebAppInstaller\Contracts\ConfigSaverInterface 
     */
    protected $defaultConfigSaver;

    /**
     * Array of \WebAppInstaller\Entity\Module ordered according to their install priority
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $installModules;

    /**
     * @param \WebAppInstaller\Options\Options  $options
     * @param \WebAppInstaller\Contracts\ConfigSaverInterface  $defaultConfigSaver
     * @param \Doctrine\Common\Collections\ArrayCollection $installModules
     */
    public function __construct(
            Options $options,
            ConfigSaverInterface $defaultConfigSaver,
            ArrayCollection $installModules,
            PhpFileCache $progressStorage
    ) {
        $this->options = $options;
        $this->defaultConfigSaver = $defaultConfigSaver;
        $this->installModules = $installModules;
        $this->progressStorage = $progressStorage;
    }

    public function checkPhpVersion(float $requiredVersion): ExecutionResultInterface {
        $isVersionValid = true;
        $message = [];
        if ((float) $requiredVersion > 0) {
            $isVersionValid = VersionCheckService::checkVersion($requiredVersion);
            $message['php'] = 'Minimum Required PHP Version : "' . $requiredVersion . '" found "' . PHP_VERSION . '"';
        }
        return ExecutionResult::create($isVersionValid, $message);
    }

    public function checkPhpExtensions(array $exts): array {
        $result = [];
        $isExtsValid = true;
        $message = '';

        foreach ($exts as $ext) {
            $isExtsValid = ExtensionCheckService::isExtensionLoaded($ext);
            $message = 'Extension "' . $ext . '" is ' . ($isExtsValid ? '' : 'not ') . 'loaded';
            $result[$ext] = ExecutionResult::create($isExtsValid, [$message]);
        }
        if (sizeof($result) == 0) {
            $result[] = ExecutionResult::create($isExtsValid, [$message]);
        }
        return $result;
    }

    public function executePreInstallStep(Module $m): ExecutionResultInterface {
        $result = ExecutionResult::create(true);
        if ($m instanceof PreInstallMethodProviderInterface) {
            $result = $m->executePreInstallScript();
        }
        $this->setCompletionStatusForStep($m->getName(), self::PREINSTALLSTEP, $result->getStatus());
        return $result;
    }

    /**
     * @param int $key
     * @return Module|null
     */
    public function getModuleFromKey(int $key): ?Module {
        return $this->installModules->get($key);
    }

    /**
     * @return Module|null
     */
    public function getModuleToProcess(): ?Module {
        $module = null;
        foreach ($this->installModules as $m) {
            if (!$this->isModuleInstalled($m->getName())) {
                $module = $m;
                break;
            }
        }
        return $module;
    }

    /**
     * @param Module $module
     * @return string|null
     */
    public function getStepToProcess(Module $module): ?string {
        $moduleName = $module->getName();
        $installParamProvider = $module->getInstallParametersProvider();
        $isPhpVersionSpecified = $module->getRequiredPhpVersion() > 0;
        $isRequiredExtsSpecified = sizeof($module->getRequiredPhpExtensions()) > 0;
        $isOptionalExtsSpecified = sizeof($module->getOptionalPhpExtensions()) > 0;
        foreach (self::$STEPS as $stepName) {

            if ($stepName == self::CHECKREQUIREMENTSTEP && !($isPhpVersionSpecified || $isRequiredExtsSpecified || $isOptionalExtsSpecified)) {
                //continue;
            }

            if ($stepName == self::PREINSTALLSTEP && !method_exists($installParamProvider, 'executePreInstallScript')) {
                //continue;
            }

            if ($stepName == self::POSTINSTALLSTEP && !method_exists($installParamProvider, 'executePostInstallScript')) {
                //continue;
            }

            if (!$this->isStepCompleted($moduleName, $stepName)) {
                return $stepName;
            }
        }
        return null;
    }

    /**
     * @param string $moduleName
     * @param bool $status
     * @return void
     */
    public function setCompletionStatusForStep(string $moduleName, string $stepName, bool $status): void {
        $this->progressStorage->save($moduleName . $stepName, $status);
        return;
    }

    /**
     * @param string $moduleName
     * @param string $stepName
     * @return bool
     */
    public function isStepCompleted(string $moduleName, string $stepName): bool {
        return (bool) $this->progressStorage->fetch($moduleName . $stepName);
    }

    /**
     * @param string $moduleName
     * @param bool $status
     * @return void
     */
    public function setCompletionStatusForModule(string $moduleName, bool $status): void {
        $this->progressStorage->save($moduleName, $status);
        return;
    }

    /**
     * @param string $moduleName
     * @return bool
     */
    public function isModuleInstalled(string $moduleName): bool {
        return (bool) $this->progressStorage->fetch($moduleName);
    }

    public function clearProgress() {
        $this->progressStorage->flushAll();
    }

    public function completeStepForModule(Module $module, string $step, $data = []): ExecutionResultInterface {
        $status = true;
        $message = [];
        $installParameterProvider = $module->getInstallParametersProvider();

        if ($step == self::CHECKREQUIREMENTSTEP) {
            $isPhpVersionSpecified = $module->getRequiredPhpVersion() > 0;
            $isRequiredExtsSpecified = sizeof($module->getRequiredPhpExtensions()) > 0;
            $isOptionalExtsSpecified = sizeof($module->getOptionalPhpExtensions()) > 0;

            if (!($isPhpVersionSpecified || $isRequiredExtsSpecified || $isOptionalExtsSpecified)) {
                $status = true;
            }
            if ($isPhpVersionSpecified) {
                $checkVersion = $this->checkPhpVersion($module->getRequiredPhpVersion());
                $status &= $checkVersion->getStatus();
                if (!$checkVersion->getStatus()) {
                    $message = array_merge($message, $checkVersion->getMessages());
                }
            }
            if ($isRequiredExtsSpecified) {
                $extResults = $this->checkPhpExtensions($module->getRequiredPhpExtensions());
                foreach ($extResults as $res) {
                    $status &= $res->getStatus();
                    if (!$res->getStatus()) {
                        $message = array_merge($message, $res->getMessages());
                    }
                }
            }
        } elseif ($step == self::PREINSTALLSTEP) {
            if ($installParameterProvider instanceof PreInstallMethodProviderInterface) {
                $result = $installParameterProvider->executePreInstallScript();
                $status = $result->getStatus();
                $message = $result->getMessages();
            }
        } elseif ($step == self::ACCEPTCONFIGSTEP) {
            $form = $module->getForm();
            $form->setData($data);
            $valid = $form->isValid();
            $configData = [];
            if ($valid) {
                $filteredData = $form->getData();
                unset($filteredData['app_installer_security']);
                unset($filteredData['app_installer_submit']);
                $configData[$module->getModuleConfigKey()] = $filteredData;
                if ($installParameterProvider instanceof ConfigValidatorInterface) {
                    $result = $installParameterProvider->validateConfig($configData);
                    $status &= $result->getStatus();
                    $message = array_merge($message, $result->getMessages());
                }
                if ($status) {
                    if ($installParameterProvider instanceof ConfigSaverInterface) {
                        $result = $installParameterProvider->save($configData);
                    } else {
                        $result = $this->defaultConfigSaver->save($configData);
                    }
                    $status &= $result->getStatus();
                    $message = array_merge($message, $result->getMessages());
                }
            }
        } elseif ($step == self::POSTINSTALLSTEP) {
            if ($installParameterProvider instanceof PostInstallMethodProviderInterface) {
                $result = $installParameterProvider->executePostInstallScript();
                $status = $result->getStatus();
                $message = $result->getMessages();
            }
        }
        $this->setCompletionStatusForStep($module->getName(), $step, $status);
        return ExecutionResult::create($status, $message);
    }

    public function isInstallationComplete(): bool {
        /* @var $m \WebAppInstaller\Entity\Module */
        $ret = true;
        foreach ($this->installModules as $m) {
            $ret &= $this->isModuleInstalled($m->getName());
        }
        return $ret;
    }
    
    public function flushProgress(){
        $this->progressStorage->flushAll();
    }

}
