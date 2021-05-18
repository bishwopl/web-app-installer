<?php

return [
    'module_config_key' => '',
    'module_title' => '',
    'module_description' => '',
    'pages' => [
        'page_1' => [
            'page_title' => 'Title',
            'page_description' => 'Description',
            'parameters' => [
                'parameter_1' => [
                    'name' => '',
                    'description' => '',
                    'type' => '',
                    'parameter_config_key' => '',
                    'filter' => [
                        'filter_1',
                        'filter_2'
                    ],
                    'validator' => [
                        'validator_1',
                        'validator_2',
                    ],
                    'child_parameters' => [
                        'child_parameter_1' => [
                            'name' => '',
                            '...',
                        ],
                    ],
                ],
            ],
        ],
        'page_2' => [
            '...',
        ],
    ],
];