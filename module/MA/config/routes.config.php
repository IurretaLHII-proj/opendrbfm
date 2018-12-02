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
						'action' => 'detail|actions',
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
								'action' => 'detail|edit|hint|image|children',
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
			'material' => [
				'type' => 'Literal',
				'options' => [
					'route' => '/material',
					'defaults' => [
						'controller' => Controller\MaterialController::class,
						'action' => 'index' 
					],
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
							]
						],
					],
					'json' => [
						'type' => 'Literal',
						'options' => [
							'route' => '/json',
							'defaults' => [
								'controller' => Controller\Js\MaterialController::class,
							]
						],
					],
				],
			],
			'operation' => [
				'type' => 'Literal',
				'options' => [
					'route' => '/op',
					'defaults' => [
						'controller' => Controller\OperationController::class,
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
								'controller' => Controller\Js\OperationController::class,
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
								'action' => 'detail|edit|delete|hints|hint|actions',
							]
						],
						'may_terminate' => true,
						'child_routes' => [
							'json' => [
								'type' => 'Literal',
								'options' => [
									'route' => '/json',
									'defaults' => [
										'controller' => Controller\Js\OperationController::class,
									],
								],
							]
						],
					],
					'type' => [
						'type' => 'Literal',
						'options' => [
							'route' => '/type',
							'defaults' => [
								'controller' => Controller\OperationTypeController::class,
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
										'controller' => Controller\Js\OperationTypeController::class,
									]
								],
							],
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
										'action' => 'detail|edit|delete|operation',
									]
								],
								'may_terminate' => true,
								'child_routes' => [
									'json' => [
										'type' => 'Literal',
										'options' => [
											'route' => '/json',
											'defaults' => [
												'controller' => Controller\Js\OperationTypeController::class,
											]
										],
									],
								],
							],
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
								'action' => 'detail|edit|delete|actions',
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
					'type' => [
						'type' => 'Literal',
						'options' => [
							'route' => '/type',
							'defaults' => [
								'controller' => Controller\HintTypeController::class,
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
