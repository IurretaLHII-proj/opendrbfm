<?php

namespace MA;

return [
    'bjyauthorize' 	   => require __DIR__ . '/bjyauthorize.config.php', 
    'router' 		   => ['routes' => require __DIR__ . '/routes.config.php'],
    'navigation'   	   => require __DIR__ . '/navigation.config.php', 
	'base_controllers' => require __DIR__ . '/base_controllers.config.php',
	'base_services'    => require __DIR__ . '/base_services.config.php',
    'zf-hal' 		   => require __DIR__ . '/hal.config.php', 
	'doctrine' => [
		'driver' => [
			__NAMESPACE__ . '_driver' => [
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => [
					__DIR__ . '/../src/Entity'
				], 
			],
			'orm_default' => [
				'drivers' => [
					__NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
				],
			],
		],
	],
	'controllers' => [
        'invokables' => [
			'Application\Controller\Index' => Controller\IndexController::class,
        ],
    ],
    'view_manager' => [ 
        'template_map' => [ 
            'layout/layout'  => __DIR__ . '/../view/layout/layout.twig',
            'zfc-user/user/login'  			=> __DIR__ . '/../view/ma/user/login.phtml',
    //        'zfc-user/user/changepassword'  => __DIR__ . '/../view/ma/user/changepassword.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
