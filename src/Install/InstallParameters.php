<?php

namespace WebAppInstaller\Install;

use WebAppInstaller\Contracts\InstallParametersProviderInterface as AA;
use WebAppInstaller\Contracts\ConfigValidatorInterface as AB;
use WebAppInstaller\Contracts\PostInstallMethodProviderInterface as AC;
use WebAppInstaller\Contracts\PreInstallMethodProviderInterface as AD;
use WebAppInstaller\Contracts\ExecutionResultInterface;
use WebAppInstaller\SetupKeys as SK;

class InstallParameters implements AA {

    /**
     * {@inheritDoc}
     */
    public function getInstallParameters(): array {
        return [
            SK::$MODULE_CONFIG_KEY => 'abc',
            SK::$MODULE_TITLE => 'Title of the module',
            SK::$MODULE_DESCRIPTION => '<p>Description of the moudule <ul><li>List 1</li><li>List 2</li></ul>',
            SK::$CALLABLE_PRE_INSTALL_SCRIPT_CLASSNAME => '',
            SK::$CALLABLE_POST_INSTALL_SCRIPT_CLASSNAME => '',
            SK::$INSTALL_PRIORITY => 1,
            SK::$REQUIRED_PHP_VERSION => 5.6,
            //SK::$REQUIRED_PHP_EXTENSIONS => ['php_intl', 'php_pdo'],
            //SK::$OPTIONAL_PHP_EXTENSIONS => ['xdebug', 'snmp'],
            SK::$PARAMETERS => [
                [
                    SK::$PARAMETER_CONFIG_KEY => 'database',
                    SK::$CHILD_PARAMETERS => [
                        [
                            SK::$PARAMETER_CONFIG_KEY => 'host',
                            SK::$INPUT_ELEMENT => [
                                'type' => \Laminas\Form\Element\Text::class,
                                'options' => [
                                    'label' => 'Host Name or Address'
                                ],
                                'attributes' => [
                                    'class' => 'form-control input',
                                    'value' => 'localhost'
                                ],
                            ],
                            SK::$INPUT_FILTER_SPEC => [
                                'required' => true,
                                'filters' => [
                                    ['name' => \Laminas\Filter\StringToLower::class],
                                    ['name' => \Laminas\Filter\StringTrim::class],
                                ],
                                'validators' => [
                                    ['name' => \Laminas\I18n\Validator\Alpha::class],
                                ],
                            ],
                        ],
                        [
                            SK::$PARAMETER_CONFIG_KEY => 'port',
                            SK::$INPUT_ELEMENT => [
                                'type' => \Laminas\Form\Element\Text::class,
                                'options' => [
                                    'label' => 'Port'
                                ],
                                'attributes' => [
                                    'class' => 'form-control input',
                                    'value' => 3306
                                ],
                            ],
                            SK::$INPUT_FILTER_SPEC => [
                                'required' => false,
                            ],
                        ],
                        [
                            SK::$PARAMETER_CONFIG_KEY => 'user',
                            SK::$INPUT_ELEMENT => [
                                'type' => \Laminas\Form\Element\Text::class,
                                'options' => [
                                    'label' => 'User Name'
                                ],
                                'attributes' => [
                                    'class' => 'form-control input',
                                ],
                            ],
                            SK::$INPUT_FILTER_SPEC => [
                                'required' => false,
                            ],
                        ],
                        [
                            SK::$PARAMETER_CONFIG_KEY => 'password',
                            SK::$INPUT_ELEMENT => [
                                'type' => \Laminas\Form\Element\Password::class,
                                'options' => [
                                    'label' => 'Password'
                                ],
                                'attributes' => [
                                    'class' => 'form-control input',
                                ],
                            ],
                            SK::$INPUT_FILTER_SPEC => [
                                'required' => false,
                            ],
                        ],
                        [
                            SK::$PARAMETER_CONFIG_KEY => 'dbname',
                            SK::$INPUT_ELEMENT => [
                                'type' => \Laminas\Form\Element\Text::class,
                                'options' => [
                                    'label' => 'Name of the database'
                                ],
                                'attributes' => [
                                    'class' => 'form-control input',
                                ],
                            ],
                            SK::$INPUT_FILTER_SPEC => [
                                'required' => false,
                            ],
                        ],
                        [
                            SK::$PARAMETER_CONFIG_KEY => 'driver',
                            SK::$INPUT_ELEMENT => [
                                'type' => \Laminas\Form\Element\Select::class,
                                'options' => [
                                    'label' => 'Driver'
                                ],
                                'attributes' => [
                                    'class' => 'form-control input',
                                ],
                            ],
                            SK::$INPUT_FILTER_SPEC => [
                                'required' => false,
                            ],
                        ],
                    ],
                ],
                [
                    SK::$PARAMETER_CONFIG_KEY => 'admin_email',
                    SK::$INPUT_ELEMENT => [
                        'type' => \Laminas\Form\Element\Email::class,
                        'options' => [
                            'label' => 'Email Address'
                        ],
                        'attributes' => [
                            'class' => 'form-control input',
                            'value' => 'bishwopl@ioe.edu.np'
                        ],
                    ],
                    SK::$INPUT_FILTER_SPEC => [
                        'required' => true,
                        'filters' => [
                            ['name' => \Laminas\Filter\StringToLower::class],
                        ],
                        'validators' => [
                            ['name' => \Laminas\Validator\EmailAddress::class],
                        ],
                    ],
                ],
            ],
        ];
    }

}
