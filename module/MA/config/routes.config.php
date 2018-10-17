<?php

namespace MA;

return [
	'zfcuser' => [
        'options' => [
            'defaults' => [
                'controller' => Controller\UserController::class,
            ],
		],
	],
	'user' => [
		'type' => 'Literal',
		'options' => [
			'route' => '/user',
			'defaults' => [
				'controller' => Controller\UserController::class,
				'action' => 'index' 
			]
		],
		'may_terminate' => true,
		'child_routes' => [
			'add' => [
				'type' => 'Literal',
				'options' => [
					'route' => '/add',
					'defaults' => [
						'action' => 'add' 
					]
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
						'action' => 'detail|changepassword',
					]
				],
				'may_terminate' => true,
				'child_routes' => [
					'json' => [
						'type' => 'Literal',
						'options' => [
							'route' => '/json',
							'defaults' => [
								'controller' => Controller\Js\UserController::class,
							],
						],
					]
				],
			],
		],
	],
	'process' => [
		'type' => 'Literal',
		'options' => [
			'route' => '/process',
			'defaults' => [
				'controller' => Controller\ProcessController::class,
				'action' => 'index' 
			],
		],
		'may_terminate' => true,
		'child_routes' => [
			'json' => [
				'type' => 'Literal',
				'options' => [
					'route' => '/json',
					'defaults' => [
						'action' => 'index',
						'controller' => Controller\Js\ProcessController::class,
					],
				],
			],
			'add' => [
				'type' => 'Literal',
				'options' => [
					'route' => '/add',
					'defaults' => [
						'action' => 'add',
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
						'action' => 'detail|edit|stage|actions',
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
						'action' => 'index' 
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
								'action' => 'detail|edit|hint|image',
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
			'hint' => [
				'type' => 'Literal',
				'options' => [
					'route' => '/hint',
					'defaults' => [
						'controller' => Controller\HintController::class,
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
								'action' => 'detail|edit|delete',
							]
						],
						'may_terminate' => true,
						'child_routes' => [
							'json' => [
								'type' => 'Literal',
								'options' => [
									'route' => '/json',
									'defaults' => [
										'controller' => Controller\Js\HintController::class,
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
