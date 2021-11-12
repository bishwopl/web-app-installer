<?php

namespace WebAppInstaller\Install;

use WebAppInstaller\Contracts\InstallParametersProviderInterface as AA;
use WebAppInstaller\Contracts\ConfigValidatorInterface as AB;
use WebAppInstaller\Contracts\PostInstallMethodProviderInterface as AC;
use WebAppInstaller\Contracts\PreInstallMethodProviderInterface as AD;
use WebAppInstaller\Contracts\ExecutionResultInterface;
use WebAppInstaller\SetupKeys as SK;
use WebAppInstaller\Entity\ExecutionResult;

class InstallParameterNew implements AA, AB, AC, AD {

    /**
     * {@inheritDoc}
     */
    public function getInstallParameters(): array {
        return [
            SK::$MODULE_CONFIG_KEY => 'abc2',
            SK::$MODULE_TITLE => 'Title of the module NEW',
            SK::$MODULE_DESCRIPTION => '<p style="text-align: justify;">Curabitur pretium tincidunt lacus. Nulla gravida orci a odio. Nullam varius, turpis et commodo pharetra, est eros bibendum elit, nec luctus magna felis sollicitudin mauris. Integer in mauris eu nibh euismod gravida. Duis ac tellus et risus vulputate vehicula. Donec lobortis risus a elit. Etiam tempor. Ut ullamcorper, ligula eu tempor congue, eros est euismod turpis, id tincidunt sapien risus a quam. Maecenas fermentum consequat mi. Donec fermentum. Pellentesque malesuada nulla a mi. Duis sapien sem, aliquet nec, commodo eget, consequat quis, neque. Aliquam faucibus, elit ut dictum aliquet, felis nisl adipiscing sapien, sed malesuada diam lacus eget erat. Cras mollis scelerisque nunc. Nullam arcu. Aliquam consequat. Curabitur augue lorem, dapibus quis, laoreet et, pretium ac, nisi. Aenean magna nisl, mollis quis, molestie eu, feugiat in, orci. In hac habitasse platea dictumst.</p>',
            SK::$CALLABLE_PRE_INSTALL_SCRIPT_CLASSNAME => '',
            SK::$CALLABLE_POST_INSTALL_SCRIPT_CLASSNAME => '',
            SK::$INSTALL_PRIORITY =>10,
            SK::$REQUIRED_PHP_VERSION => '7.0',
            //SK::$REQUIRED_PHP_EXTENSIONS => ['ldap', 'pdo_mysql'],
            //SK::$OPTIONAL_PHP_EXTENSIONS => ['xdebug', 'snmp'],
            SK::$PARAMETERS => [
                /*[
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
                 * 
                 */
                [
                    SK::$PARAMETER_CONFIG_KEY => 'admin_email',
                    SK::$INPUT_ELEMENT => [
                        'type' => \Laminas\Form\Element\Email::class,
                        'options' => [
                            'label' => 'Administrator Email Address'
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

    /**
     * {@inheritDoc}
     */
    public function executePostInstallScript(): ExecutionResultInterface {
        return ExecutionResult::create(true, ['hello']);
    }

    /**
     * {@inheritDoc}
     */
    public function executePreInstallScript(): ExecutionResultInterface {
        return ExecutionResult::create(true, ['hello']);
    }

    /**
     * {@inheritDoc}
     */
    public function validateConfig(array $config): ExecutionResultInterface {
        return ExecutionResult::create(true, ['hello']);
    }

}
