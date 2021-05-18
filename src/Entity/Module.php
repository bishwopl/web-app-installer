<?php

namespace WebAppInstaller\Entity;

use WebAppInstaller\SetupKeys as SK;
use WebAppInstaller\Entity\Parameter;
use WebAppInstaller\Contracts\InstallParametersProviderInterface;
use Webmozart\Assert\Assert;
use Laminas\Form\Factory as FormFactory;
use Laminas\InputFilter\Factory as InputFilterFactory;
use Laminas\Form\Element;
use Laminas\Form\Form;
use Psr\Container\ContainerInterface;
use Doctrine\Common\Collections\ArrayCollection;

class Module {

    /**
     * @var \WebAppInstaller\Contracts\InstallParametersProviderInterface 
     */
    protected $installParametersProvider;

    /**
     * Config key for the module. This key will be used in configuration array.
     * @var string 
     */
    protected $moduleConfigKey;

    /**
     * Title of the module to be displayed during installation 
     * @var string 
     */
    protected $moduleTitle;

    /**
     * Description of the module to be displayed during installation. Can use HTML as well.
     * @var string 
     */
    protected $moduleDescription;

    /**
     * Array of parameters
     * @var ArrayCollection 
     */
    protected $parameters;

    /**
     * @var \Laminas\Form\Form 
     */
    protected $form = NULL;

    /**
     * FQCN of invokable class for pre-install operations
     * @var string 
     */
    protected $callablePreInstallScriptClassName;

    /**
     * FQCN of invokable class for post-install operations
     * @var string 
     */
    protected $callablePostInstallScriptClassName;

    /**
     * Module installation priority. Higher number means lower priority.
     * @var string 
     */
    protected $installPriority;

    public function __construct(InstallParametersProviderInterface $installParametersProvider) {
        $this->installParametersProvider = $installParametersProvider;
        $this->parameters = new ArrayCollection();
        $this->initialize();
    }

    /**
     * @param array $moduleConfig
     * @return \WebAppInstaller\Entity\Module
     */
    private function initialize(): void {
        $moduleConfig = $this->installParametersProvider->getInstallParameters();
        $this->moduleConfigKey = $moduleConfig[SK::$MODULE_CONFIG_KEY] ?? '';
        $this->moduleTitle = $moduleConfig[SK::$MODULE_TITLE] ?? '';
        $this->moduleDescription = $moduleConfig[SK::$MODULE_DESCRIPTION] ?? '';
        $this->callablePreInstallScriptClassName = $moduleConfig[SK::$CALLABLE_PRE_INSTALL_SCRIPT_CLASSNAME] ?? '';
        $this->callablePostInstallScriptClassName = $moduleConfig[SK::$CALLABLE_POST_INSTALL_SCRIPT_CLASSNAME] ?? '';
        $this->installPriority = $moduleConfig[SK::$INSTALL_PRIORITY] ?? '';

        Assert::keyExists($moduleConfig, SK::$PARAMETERS);

        foreach ($moduleConfig[SK::$PARAMETERS] as $parameConfig) {
            $this->parameters->add(Parameter::createFromArray($parameConfig));
        }
        $this->initializeForm();
        return;
    }

    /**
     * Initialize form 
     * @return void
     */
    private function initializeForm($force = false): void {
        if ($this->form instanceof Form && $force == false) {
            return;
        }
        $formFactory = new FormFactory();
        $filterFactory = new InputFilterFactory();
        $formSpec = [];
        $filterSpec = [];
        foreach ($this->parameters as $param) {
            Assert::isInstanceOf($param, Parameter::class);
            $formSpec[$param->getChildParameterCount() == 0 ? 'elements' : 'fieldsets'][] = [
                'spec' => $param->getElementSpec()
            ];
            $filterSpec[$param->getParameterConfigKey()] = $param->getInputFilterSpec();
        }

        $this->form = $formFactory->createForm($formSpec);
        $this->form->setInputFilter($filterFactory->createInputFilter($filterSpec));

        $csrf = new Element\Csrf('app_installer_security');
        $csrf->getCsrfValidator()->setTimeout(600);
        $this->form->add($csrf);

        $submitElement = new Element\Button('app_installer_submit');
        $submitElement->setLabel('Submit')->setAttributes([
            'type' => 'submit',
            'class' => 'btn btn-sm btn-primary'
        ]);
        $this->form->add($submitElement);
        return;
    }

    public function getForm(): Form {
        return $this->form;
    }

    public function setData($data): void {
        $this->form->setData($data);
        return;
    }

    public function isValid(): bool {
        return $this->form->isValid();
    }

    public function getFormMessages(): array {
        return $this->form->getMessages();
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection 
     */
    public function getParameters(): ArrayCollection {
        return $this->parameters;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection 
     */
    public function getPages(): ArrayCollection {
        return $this->pages;
    }

    /**
     * @return string
     */
    public function getModuleConfigKey(): string {
        return $this->moduleConfigKey;
    }

    /**
     * @return string
     */
    public function getModuleTitle(): string {
        return $this->moduleTitle;
    }

    /**
     * @return string
     */
    public function getModuleDescription(): string {
        return $this->moduleDescription;
    }

    /**
     * @return string
     */
    public function getCallablePreInstallScriptClassName(): string {
        return $this->callablePreInstallScriptClassName;
    }

    /**
     * @return string
     */
    public function getCallablePostInstallScriptClassName(): string {
        return $this->callablePostInstallScriptClassName;
    }

    /**
     * @return string
     */
    public function getInstallPriority(): string {
        return $this->installPriority;
    }

    /**
     * @return array
     */
    public function getConfigValues(): array {
        $values = $this->form->getData();
        unset($values['security']);
        unset($values['submit']);
        return [
            $this->moduleConfigKey => $values
        ];
    }

    public function executePreInstallScript(ContainerInterface $container) {
        $obj = $this->getObject($container, $this->callablePreInstallScriptClassName);
        Assert::isCallable($obj);
        $obj();
    }

    public function executePostInstallScript(ContainerInterface $container) {
        $obj = $this->getObject($container, $this->callablePostInstallScriptClassName);
        Assert::isCallable($obj);
        $obj();
    }

    /**
     * @return float
     */
    public function getRequiredPhpVersion(): float {
        $config = $this->installParametersProvider->getInstallParameters();
        return isset($config[SK::$REQUIRED_PHP_VERSION]) ? $config[SK::$REQUIRED_PHP_VERSION] : 0;
    }

    /**
     * @return array
     */
    public function getRequiredPhpExtensions(): array {
        $config = $this->installParametersProvider->getInstallParameters();
        return isset($config[SK::$REQUIRED_PHP_EXTENSIONS]) ? $config[SK::$REQUIRED_PHP_EXTENSIONS] : [];
    }
    
    /**
     * @return array
     */
    public function getOptionalPhpExtensions(): array {
        $config = $this->installParametersProvider->getInstallParameters();
        return isset($config[SK::$OPTIONAL_PHP_EXTENSIONS]) ? $config[SK::$OPTIONAL_PHP_EXTENSIONS] : [];
    }
    
    /**
     * @return string
     */
    public function getName(): string {
        return get_class($this->installParametersProvider);
    }

    /**
     * @return InstallParametersProviderInterface
     */
    public function getInstallParametersProvider(): InstallParametersProviderInterface {
        return $this->installParametersProvider;
    }
    
    /**
     * @param ContainerInterface $container
     * @param type $className
     * @return object
     * @throws \Exception
     */
    private function getObject(ContainerInterface $container, $className): object {
        try {
            if ($container->has($className)) {
                return $container->get($className);
            } else {
                return new $className();
            }
        } catch (Exception $ex) {
            throw new \Exception('Cannot initialize ' . $className . '.Reason : ' . $ex->getMessage());
        }
    }
    

}
