<?php

namespace MA;

return [
	'process' => [
		'type' => 'Literal',
		'options' => [
			'route' => '/process',
			'defaults' => [
				'controller' => Controller\ProcessController::class,
				'action' => 'add' 
			]
		],
		'may_terminate' => true,
		'child_routes' => [
			'detail' => [
				'type' => 'Segment',
				'options' => [
					'route' => '/:id[/:action]',
					'defaults' => [
						'action' => 'detail',
					],
					'constraints' => [
						'id' => '\d+',
						'action' => 'detail|stage',
					]
				],
				'may_terminate' => true,
				'child_routes' => [
					'json' => [
						'type' => 'Literal',
						'options' => [
							'route' => '/json',
							'defaults' => [
								'controller' => Controller\Js\ProcessController::class,
							],
						],
					]
				],
			],
			'stage' => [
				'type' => 'Literal',
				'options' => [
					'route' => '/stage',
					'defaults' => [
						'controller' => Controller\StageController::class,
						'action' => 'add' 
					],
				],
				'may_terminate' => true,
				'child_routes' => [
					'image' => [
						'type' => 'Literal',
						'options' => [
							'route' => '/image/json',
							'defaults' => [
								'action' => 'image',
								'controller' => Controller\Js\StageController::class,
							],
						],
					],
					'detail' => [
						'type' => 'Segment',
						'options' => [
							'route' => '/:id[/:action]',
							'defaults' => [
								'action' => 'detail',
							],
							'constraints' => [
								'id' => '\d+',
								'action' => 'detail|edit|image',
							]
						],
						'may_terminate' => true,
						'child_routes' => [
							'json' => [
								'type' => 'Literal',
								'options' => [
									'route' => '/json',
									'defaults' => [
										'controller' => Controller\Js\StageController::class,
									],
								],
							]
						],
					],
				],
			],
		],
	],
	'image' => [
		'type' => 'Literal',
		'options' => [
			'route' => '/image',
			'defaults' => [
				'controller' => Controller\ImageController::class,
				'action' => 'index' 
			],
		],
		'may_terminate' => true,
		'child_routes' => [
			'detail' => [
				'type' => 'Segment',
				'options' => [
					'route' => '/:id[/:action]',
					'defaults' => [
						'action' => 'detail',
					],
					'constraints' => [
						'id' => '\d+',
						'action' => 'detail',
					]
				],
			],
		],
	],
];
