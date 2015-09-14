<?php
return [
    'router' => include __DIR__ . '/module/router.config.php',
    'view_helpers'=> include __DIR__ . '/module/viewhelpers.config.php',

    'frontend' => [ ],

    'di' => include __DIR__ . '/module/di.config.php',

    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/exception',
        'template_map' => [
            'layout/frontend/common'=> __DIR__ . '/../view/layout/layout.phtml',
            'layout/frontend/gallery'=> __DIR__ . '/../view/layout/gallery.phtml',
            'error/404'             => __DIR__ . '/../view/error/404.phtml',
            'error/exception'       => __DIR__ . '/../view/error/exception.phtml',
            'error/403'             => __DIR__ . '/../view/error/403.phtml'
        ],
        'template_path_stack' => [
            'frontend'=>__DIR__ . '/../view',
        ],
        'strategies' => [
          'ViewJsonStrategy'
        ]
    ]
];
