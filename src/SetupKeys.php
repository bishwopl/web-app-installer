<?php

namespace WebAppInstaller;

class SetupKeys {
    
    /**
     * Version number of minimum required PHP version
     * <code>
     * <p><b>Type</b> : string</p>
     * </code> 
     */
    public static $REQUIRED_PHP_VERSION = 'required_php_veriosn';
    
    /**
     * Array of required php extensions
     * <code>
     * <p><b>Type</b> : array</p>
     * </code> 
     */
    public static $REQUIRED_PHP_EXTENSIONS = 'required_php_extension';
    
    /**
     * Array of oprional php extensions
     * <code>
     * <p><b>Type</b> : array</p>
     * </code> 
     */
    public static $OPTIONAL_PHP_EXTENSIONS = 'optional_php_extension';

    /**
     * Config key for the module. This key will be used in configuration array.
     * <code>
     * <p><b>Type</b> : string</p>
     * </code>
     */
    public static $MODULE_CONFIG_KEY = 'module_config_key';

    /**
     * Title of the module to be displayed during installation 
     * <code>
     * <p><b>Type</b> : string</p>
     * </code>
     */
    public static $MODULE_TITLE = 'module_title';

    /**
     * Description of the module to be displayed during installation. Can use HTML as well.
     * <code>
     * <p><b>Type</b> : string</p>
     * </code>
     */
    public static $MODULE_DESCRIPTION = 'module_description';

    /**
     * If module installation requires multiple steps config parameters can be divided into pages
     * with relevant parameters in same page.
     * <code>
     * <p><b>Type</b> : array</p>
     * </code>
     */
    public static $PAGES = 'pages';

    /**
     * Title of the page to be displayed during installation
     * <code>
     * <p><b>Type</b> : string</p>
     * </code> 
     */
    public static $PAGE_TITLE = 'page_title';

    /**
     * Description of the page to be displayed during installation. Can use HTML as well.
     * <code>
     * <p><b>Type</b> : string</p>
     * </code>
     */
    public static $PAGE_DESCRIPTION = 'page_description';

    /**
     * Array of parameters
     * <code>
     * <p><b>Type</b> : array</p>
     * </code>
     */
    public static $PARAMETERS = 'parameters';

    /**
     * Display name of the parameter
     * <code>
     * <p><b>Type</b> : string</p>
     * </code>
     */
    public static $PARAMETER_NAME = 'parameter_name';

    /**
     * Description of the parameter to be displayed during installation
     * <code>
     * <p><b>Type</b> : string</p>
     * </code>
     */
    public static $PARAMETER_DESCRIPTION = 'parameter_description';

    /**
     * Type of the parameter
     * <code>
     * <p><b>Type</b> : mixed</p>
     * </code> 
     */
    public static $PARAMETER_TYPE = 'parameter_type';

    /**
     * Configuration key of the parameter
     * <code>
     * <p><b>Type</b> : string</p>
     * </code>
     */
    public static $PARAMETER_CONFIG_KEY = 'parameter_config_key';
    
    /**
     * Input element definition in array form for the parameter
     * <code>
     * <p><b>Type</b> : array</p>
     * </code>
     */
    public static $INPUT_ELEMENT = 'input_element';

    /**
     * Array of filters to be applied for the parameter
     * <code>
     * <p><b>Type</b> : array</p>
     * </code>
     */
    public static $PARAMETER_FILTERS = 'parameter_filter';
    
    /**
     * Array of filters and validators to be applied for the parameter
     * <code>
     * <p><b>Type</b> : array</p>
     * </code>
     */
    public static $INPUT_FILTER_SPEC = 'input_filter_spec';

    /**
     * Name of filter
     * <code>
     * <p><b>Type</b> : string</p>
     * </code>
     */
    public static $FILTER_NAME = 'name';

    /**
     * Options for filter
     * <code>
     * <p><b>Type</b> : array</p>
     * </code>
     */
    public static $FILTER_OPTIONS = 'options';

    /**
     * Name of validator
     * <code>
     * <p><b>Type</b> : string</p>
     * </code>
     */
    public static $VALIDATOR_NAME = 'name';

    /**
     * Options for validator
     * <code>
     * <p><b>Type</b> : array</p>
     * </code>
     */
    public static $VALIDATOR_OPTIONS = 'options';

    /**
     * Array of validators to be used to validate the parameter
     * <code>
     * <p><b>Type</b> : array</p>
     * </code>
     */
    public static $PARAMETER_VALIDATORS = 'parameter_validator';

    /**
     * Array of child parameters
     * <code>
     * <p><b>Type</b> : array</p>
     * </code>
     */
    public static $CHILD_PARAMETERS = 'child_parameters';

    /**
     * FQCN of invokable class for implementation of custom filter
     * <code>
     * <p><b>Type</b> : string</p>
     * </code>
     */
    public static $CALLABLE_FILTER_CLASSNAME = 'callable_filter_class_name';

    /**
     * FQCN of invokable class for implementation of custom validator
     * <code>
     * <p><b>Type</b> : string</p>
     * </code>
     */
    public static $CALLABLE_VALIDATOR_CLASSNAME = 'callable_validator_class_name';

    /**
     * FQCN of invokable class for pre-install operations
     * <code>
     * <p><b>Type</b> : string</p>
     * </code>
     */
    public static $CALLABLE_PRE_INSTALL_SCRIPT_CLASSNAME = 'callable_pre_install_script_class_name';

    /**
     * FQCN of invokable class for post-install operations
     * <code>
     * <p><b>Type</b> : string</p>
     * </code>
     */
    public static $CALLABLE_POST_INSTALL_SCRIPT_CLASSNAME = 'callable_post_install_script_class_name';

    /**
     * Module installation priority. Higher number means lower priority.
     * <code>
     * <p><b>Type</b> : integer</p>
     * </code>
     */
    public static $INSTALL_PRIORITY = 'install_priority';

    /**
     * True if parameter is required for installation. Otherwise false.
     * <code>
     * <p><b>Type</b> : boolean</p>
     * </code>
     */
    public static $REQUIRED = 'required';

}
