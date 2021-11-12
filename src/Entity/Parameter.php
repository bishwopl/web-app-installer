<?php

namespace WebAppInstaller\Entity;

use WebAppInstaller\SetupKeys as SK;
use Doctrine\Common\Collections\ArrayCollection;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\Input;
use Webmozart\Assert\Assert;

class Parameter {

    /**
     * Name of the parameter to be displayed during installation.
     * @var string 
     */
    protected $parameterName;

    /**
     * Description of the parameter to be displayed during installation.
     * @var string 
     */
    protected $parameterDescription;

    /**
     * Type of the parameter
     * @var string 
     */
    protected $parameterType;

    /**
     * Configuration key of the parameter
     * @var string 
     */
    protected $parameterConfigKey;

    /**
     * Value of the parameter
     * @var mixed
     */
    protected $parameterValue;

    /**
     * Error messages from validator
     * @var array 
     */
    protected $messages = [];

    /**
     * Array of child parameters
     * @var \Doctrine\Common\Collections\ArrayCollection 
     */
    protected $childParameters;

    /**
     * Invokable object for implementation of custom filter
     * @var string
     */
    protected $callableFilter = NULL;

    /**
     * Invokable object for implementation of custom validator
     * @var string
     */
    protected $callableValidator = NULL;

    /**
     * Form element to be used for the parameter.
     * @var array
     */
    protected $inputElement = NULL;

    /**
     * Input filter and validator spec
     * @var array
     */
    protected $inputFilterSpec = [];

    /**
     * True if parameter is required for installation. Otherwise false.
     * @var boolean 
     */
    protected $isRequired = true;

    private function __construct() {
        $this->childParameters = new ArrayCollection();
    }

    public function getInputElement(): array {
        return $this->inputElement;
    }

    public function getChildParameters(): ArrayCollection {
        return $this->childParameters;
    }
    
    /**
     * @return int
     */
    public function getChildParameterCount(): int {
        return $this->childParameters->count();
    }

    /**
     * @return string
     */
    public function getParameterName(): string {
        return $this->parameterName;
    }

    /**
     * @return string
     */
    public function getParameterDescription(): string {
        return $this->parameterDescription;
    }

    /**
     * @return string
     */
    public function getParameterConfigKey(): string {
        return $this->parameterConfigKey;
    }

    /**
     * @return mixed
     */
    public function getParameterValue() {
        return $this->parameterValue;
    }
    
    /**
     * Input element specification
     * @return array
     */
    public function getElementSpec(): array {
        $ret = $this->inputElement;
        $ret['name'] = $this->parameterConfigKey;
        if ($this->childParameters->count() > 0) {
            $ret['elements'] = $this->childParameters->map(function($a) {
                        return ['spec' => $a->getElementSpec()];
                    })->toArray();
        }
        return $ret;
    }

    /**
     * Get input filter and validator spec
     * @return array
     */
    public function getInputFilterSpec(): array {
        $ret = $this->childParameters->count()>0?$this->childParameters->map(function($a) {
                    $b = $a->inputFilterSpec;
                    $b['name'] = $a->parameterConfigKey;
                    return $b;
                })->toArray():$this->inputFilterSpec;
        $ret['type'] = $this->childParameters->count()>0 ? InputFilter::class : Input::class;
        return $ret;
    }
    
    /**
     * @param array $paramConfig
     * @return \WebAppInstaller\Entity\Parameter
     */
    public static function createFromArray(array $paramConfig): Parameter {
        $parameter = new Parameter();

        Assert::keyExists($paramConfig, SK::$PARAMETER_CONFIG_KEY);

        $parameter->parameterConfigKey = $paramConfig[SK::$PARAMETER_CONFIG_KEY];
        $parameter->parameterName = $paramConfig[SK::$PARAMETER_NAME] ?? '';
        $parameter->parameterDescription = $paramConfig[SK::$PARAMETER_DESCRIPTION] ?? '';

        if (isset($paramConfig[SK::$INPUT_FILTER_SPEC]) && is_array($paramConfig[SK::$INPUT_FILTER_SPEC])) {
            $parameter->inputFilterSpec = $paramConfig[SK::$INPUT_FILTER_SPEC];
        }

        if (isset($paramConfig[SK::$INPUT_ELEMENT]) && is_array($paramConfig[SK::$INPUT_ELEMENT])) {
            $parameter->inputElement = $paramConfig[SK::$INPUT_ELEMENT];
        }

        $parameter->callableFilterClassName = $paramConfig[SK::$CALLABLE_FILTER_CLASSNAME] ?? NULL;
        $parameter->callableValidatorClassName = $paramConfig[SK::$CALLABLE_VALIDATOR_CLASSNAME] ?? NULL;

        if (isset($paramConfig[SK::$CHILD_PARAMETERS]) && is_array($paramConfig[SK::$CHILD_PARAMETERS])) {
            foreach ($paramConfig[SK::$CHILD_PARAMETERS] as $childConfig) {
                $parameter->childParameters->add(Parameter::createFromArray($childConfig));
            }
        }
        return $parameter;
    }

}
