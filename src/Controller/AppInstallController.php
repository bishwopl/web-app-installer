<?php

namespace WebAppInstaller\Controller;

use WebAppInstaller\Service\InstallerService;
use WebAppInstaller\Entity\Module;
use WebAppInstaller\Form\BaseForm;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Mvc\MvcEvent;

class AppInstallController extends AbstractActionController {

    public static $ACTIONS = [
        InstallerService::CHECKREQUIREMENTSTEP => 'check-requirements',
        InstallerService::PREINSTALLSTEP => 'pre-install',
        InstallerService::ACCEPTCONFIGSTEP => 'accept-config',
        InstallerService::POSTINSTALLSTEP => 'post-install',
        InstallerService::INSTALLATIONCOMPLETE => 'installation-complete',
    ];

    /**
     * @var \WebAppInstaller\Service\InstallerService $installerService
     */
    protected $installerService = null;

    /**
     * @var \WebAppInstaller\Entity\Module 
     */
    protected $currentModule = null;

    /**
     * @var string
     */
    protected $currentStep = null;

    /**
     * @var \Laminas\View\Model\ViewModel
     */
    protected $viewModel;

    public function __construct(InstallerService $installerService) {
        $this->installerService = $installerService;
        $this->viewModel = new ViewModel();
    }

    public function indexAction(): ViewModel {
        $this->setCorrectModuleAndStep(__METHOD__);
        return $this->getViewModel();
    }

    public function preInstallAction(): ViewModel {
        $this->setCorrectModuleAndStep(__METHOD__);
        $installParameterProvider = $this->currentModule->getInstallParametersProvider();
        $exeResult = null;
        $form = new BaseForm('pre-install-form');

        if ($this->request->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();
            $form->setData($data);
            if ($form->isValid()) {
                $result = $this->installerService->completeStepForModule($this->currentModule, InstallerService::PREINSTALLSTEP);
                if ($result->getStatus() == false) {
                    $form->get('app_installer_security')->setMessages(array_values($result->getMessages()));
                } else {
                    $this->setCorrectModuleAndStep(__METHOD__);
                }
            }
        }

        if (method_exists($installParameterProvider, 'executePreInstallScript')) {
            $exeResult = $installParameterProvider->executePreInstallScript();
        }
        $form->get('app_installer_submit')
                ->setLabel(((is_object($exeResult) && $exeResult->getStatus()) || $exeResult == null) ? 'Next' : 'Ignore and Continue'
        );
        $this->viewModel->setVariables([
            'module' => $this->currentModule,
            'executionResult' => $exeResult,
            'form' => $form,
        ]);

        return $this->getViewModel();
    }

    public function checkRequirementsAction(): ViewModel {
        $this->setCorrectModuleAndStep(__METHOD__);

        $resultVesion = $this->installerService->checkPhpVersion($this->currentModule->getRequiredPhpVersion());
        $resultRequiredExts = $this->installerService->checkPhpExtensions($this->currentModule->getRequiredPhpExtensions());
        $resultOptionalExts = $this->installerService->checkPhpExtensions($this->currentModule->getOptionalPhpExtensions());
        $form = new BaseForm('check-requirement-form');
        $form->get('app_installer_submit')->setLabel('Next');

        if ($this->request->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();
            $form->setData($data);
            if ($form->isValid()) {
                $result = $this->installerService->completeStepForModule(
                        $this->currentModule,
                        InstallerService::CHECKREQUIREMENTSTEP
                );
                if ($result->getStatus() == false) {
                    $form->get('app_installer_security')->setMessages(array_values($result->getMessages()));
                } else {
                    $this->setCorrectModuleAndStep(__METHOD__);
                }
            }
        }

        $this->viewModel->setVariables([
            'resultVersion' => $resultVesion,
            'resultRequiredExtension' => $resultRequiredExts,
            'resultOptionalExtension' => $resultOptionalExts,
            'module' => $this->currentModule,
            'form' => $form,
        ]);

        return $this->getViewModel();
    }

    public function acceptConfigAction() {
        $this->setCorrectModuleAndStep(__METHOD__);
        $form = $this->currentModule->getForm();
        $form->get('app_installer_submit')->setLabel('Next');

        if ($this->request->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();
            $form->setData($data);
            if ($form->isValid()) {
                $result = $this->installerService->completeStepForModule(
                        $this->currentModule,
                        InstallerService::ACCEPTCONFIGSTEP,
                        $data
                );
                if ($result->getStatus() == false) {
                    $form->get('app_installer_security')->setMessages(array_values($result->getMessages()));
                } else {
                    $this->setCorrectModuleAndStep(__METHOD__);
                }
            }
        }

        $this->viewModel->setVariables([
            'module' => $this->currentModule,
            'form' => $form,
        ]);
        return $this->getViewModel();
    }

    public function postInstallAction(): ViewModel {
        $this->setCorrectModuleAndStep(__METHOD__);
        $installParameterProvider = $this->currentModule->getInstallParametersProvider();
        $exeResult = null;
        $form = new BaseForm('post-install-form');

        if ($this->request->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();
            $form->setData($data);
            if ($form->isValid()) {
                $result = $this->installerService->completeStepForModule($this->currentModule, InstallerService::POSTINSTALLSTEP);
                if ($result->getStatus() == false) {
                    $form->get('app_installer_security')->setMessages(array_values($result->getMessages()));
                } else {
                    $this->setCorrectModuleAndStep(__METHOD__);
                }
            }
        }

        if (method_exists($installParameterProvider, 'executePostInstallScript')) {
            $exeResult = $installParameterProvider->executePostInstallScript();
        }
        $form->get('app_installer_submit')
                ->setLabel(((is_object($exeResult) && $exeResult->getStatus()) || $exeResult == null) ? 'Next' : 'Ignore and Continue'
        );
        $this->viewModel->setVariables([
            'module' => $this->currentModule,
            'executionResult' => $exeResult,
            'form' => $form,
        ]);

        return $this->getViewModel();
    }

    public function installationCompleteAction(): ViewModel {
        $form = new BaseForm('post-install-form');
        if (!$this->installerService->isInstallationComplete()) {
            $this->setCorrectModuleAndStep(__METHOD__);
            if ($this->request->isPost()) {
                $this->installerService->setCompletionStatusForModule($this->currentModule->getName(), true);
                $this->setCorrectModuleAndStep(__METHOD__);
            }
        }
        $form->get('app_installer_submit')->setLabel('Continue');
        $this->viewModel->setVariables([
            'module' => $this->currentModule,
            'pageTitle' => 'Installation Complete',
            'form' => $form,
            'allModulesInstalled' => $this->installerService->isInstallationComplete()
        ]);
        return $this->getViewModel();
    }

    private function setCorrectModuleAndStep($calledAction, string $stepName = '') {
        $calledAction = trim(str_replace(__CLASS__ . '::', '', $calledAction));
        $this->currentModule = $this->installerService->getModuleToProcess();
        if ($this->currentModule instanceof Module) {
            $this->currentStep = $this->installerService->getStepToProcess($this->currentModule);
        } else {
            return $this->redirect()->toRoute('web-app-installer', [
                        'action' => self::$ACTIONS[InstallerService::INSTALLATIONCOMPLETE],
            ]);
        }

        if ($calledAction !== $this->getMethodFromAction(self::$ACTIONS[$this->currentStep])) {
            $currectAction = self::$ACTIONS[$this->currentStep];
            return $this->redirect()->toRoute('web-app-installer', [
                        'action' => $currectAction,
            ]);
        }
        return;
    }

    private function getViewModel() {
        $this->layout()->setTemplate('web-app-installer/layout');
        $this->viewModel->setOption('lockLayout', true);
        return $this->viewModel;
    }

}
