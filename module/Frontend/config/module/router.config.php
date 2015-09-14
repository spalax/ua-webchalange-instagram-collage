<?php
return [
    'routes' => [
        'frontend' => [
            'type' => 'literal',
            'options' => [
                'route' => '/',
                'defaults' => [
                    'controller' => 'Frontend-Controller-Index'
                ]
            ],
            'may_terminate' => true,
            'child_routes' => [
                'login' => [
                    'type' => 'literal',
                    'options' => [
                        'route' => 'login/success',
                        'defaults' => [
                            'controller' => 'Frontend-Controller-Login-Success'
                        ]
                    ]
                ],
                'logout' => [
                    'type' => 'literal',
                    'options' => [
                        'route' => 'logout',
                        'defaults' => [
                            'controller' => 'Frontend-Controller-Logout'
                        ]
                    ]
                ],
                'gallery' => [
                    'type' => 'literal',
                    'options' => [
                        'route' => 'gallery',
                    ],
                    'may_terminate' => false,
                    'child_routes' => [
                        'configure' => [
                            'type' => 'literal',
                            'options' => [
                                'route' => '/configure',
                                'defaults' => [
                                    'controller' => 'Frontend-Controller-Gallery-Configure'
                                ]
                            ]
                        ],
                        'preview' => [
                            'type' => 'method',
                            'options' => [
                                'verb' => 'get',
                                'defaults' => [
                                    'controller' => 'Frontend-Controller-Gallery-Preview'
                                ]
                            ]
                        ],
                        'collage' => [
                            'type' => 'method',
                            'options' => [
                                'verb' => 'post',
                                'defaults' => [
                                    'controller' => 'Frontend-Controller-Gallery-Collage'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];
