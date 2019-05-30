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
            'zfc-user/user/login'  			=> __DIR__ . '/../view/ma/user/login.twig',
    //        'zfc-user/user/changepassword'  => __DIR__ . '/../view/ma/user/changepassword.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
	'translator' => [
		'locale' => 'es_ES',
	],
	'service_manager' => [
		'factories' => [
			//Override DOMPDFModule bug 
			//https://github.com/raykolbe/DOMPDFModule/pull/36/commits/21fd356c8d79c111116555fba0ef94bd24594d95#diff-c1c8ed7a4ddcb50eea2143d9f3f5bc02
           	'ViewPdfRenderer' => Factory\DOMPDF\ViewPdfRendererFactory::class,
			//'SessionConfig'  => Zend\Session\Service\SessionConfigFactory::class,
			\Zend\Session\Config\ConfigInterface::class => \Zend\Session\Service\SessionConfigFactory::class,	
			\Zend\Session\SessionInterface::class => \Zend\Session\Service\SessionManagerFactory::class,
		],
	],
	'session_manager' => [
		// SessionManager config: validators, etc
	],
	'session_config' => [
		'cache_expire' => 86400,
		'cookie_lifetime' => 86400,
	],	
];
