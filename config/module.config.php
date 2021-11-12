<?php

namespace WebAppInstaller;

use Laminas\Router\Http\Segment;

return [
    'web_app_installer' => [
        'install_hooks' => [
            //Install\InstallParameters::class,
            //Install\InstallParameterNew::class,
        ],
        'default_config_directory' => __DIR__ . '/../../../../config/autoload',
        'default_config_file_name' => 'app.config.local.php',
        'data_directory'           => __DIR__ . '/../../../../data/AppInstaller',
    ],
    'controllers' => [
        'factories' => [
            Controller\AppInstallController::class => Factory\Controller\AppInstallControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Options\Options::class => Factory\Options\OptionsFactory::class,
            Service\InstallerService::class => Factory\Service\InstallerServiceFactory::class,
            Service\ConfigSaverService::class => Factory\Service\ConfigSaverServiceFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'web-app-installer' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/install[/:action]',
                    'defaults' => [
                        'controller' => Controller\AppInstallController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'circlical' => [
        'user' => [
            'guards' => [
                'bpl-admin' => [
                    'controllers' => [
                        Controller\AppInstallController::class => [
                            'default' => [],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'web-app-installer' => __DIR__ . '/../view',
        ],
        'template_map' => [
            'web-app-installer/layout' => __DIR__ . '/../view/layout/install-layout.phtml',
            'web-app-installer/pagination' => __DIR__ . '/../view/partial/pagination.phtml',
            'web-app-installer/form' => __DIR__ . '/../view/partial/form.phtml',
        ],
    ],
];
