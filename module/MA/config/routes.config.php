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
			'list' => [
				'type' => 'Literal',
				'options' => [
					'route' => '/list',
					'defaults' => [
						'action' => 'list' 
					]
				],
			],
			'json' => [
				'type' => 'Literal',
				'options' => [
					'route' => '/json',
					'defaults' => [
						'controller' => Controller\Js\UserController::class,
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
						'action' => 'detail|edit|actions|notifications',
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
	'customer' => [
		'type' => 'Literal',
		'options' => [
			'route' => '/customer',
			'defaults' => [
				'controller' => Controller\CustomerController::class,
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
			'json' => [
				'type' => 'Literal',
				'options' => [
					'route' => '/json',
					'defaults' => [
						'controller' => Controller\Js\CustomerController::class,
						'action' => 'index' 
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
						'action' => 'detail',
					]
				],
				'may_terminate' => true,
				'child_routes' => [
					'json' => [
						'type' => 'Literal',
						'options' => [
							'route' => '/json',
							'defaults' => [
								'controller' => Controller\Js\CustomerController::class,
							],
						],
					]
				],
			],
		],
	],
	'home' => [
		'type' => 'Literal',
		'options' => [
			'route' => '/',
			'defaults' => [
				'controller' => Controller\ProcessController::class,
				'action' => 'index' 
			],
		],
	],
	'action' => [
		'type' => 'Literal',
		'options' => [
			'route' => '/action',
			'defaults' => [
				'controller' => Controller\ActionController::class,
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
						'controller' => Controller\Js\ActionController::class,
					],
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
				'may_terminate' => true,
				'child_routes' => [
					'add' => [
						'type' => 'Literal',
						'options' => [
							'route' => '/add',
							'defaults' => [
								'action' => 'add',
							],
						],
					],
				],
			],
			'tpl' => [
				'type' => 'Literal',
				'options' => [
					'route' => '/tpl',
					'defaults' => [
						'action' => 'tpl',
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
						'action' => 'detail|edit|delete|clone|report|version|actions',
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
			'notification' => [
				'type' => 'Literal',
				'options' => [
					'route' => '/notification',
					'defaults' => [
						'controller' => Controller\NotificationController::class,
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
								'action' => 'detail|read|delete',
							]
						],
						'may_terminate' => true,
						'child_routes' => [
							'json' => [
								'type' => 'Literal',
								'options' => [
									'route' => '/json',
									'defaults' => [
										'controller' => Controller\Js\NotificationController::class,
									],
								],
							]
						],
					],
				],
			],
			'comment' => [
				'type' => 'Literal',
				'options' => [
					'route' => '/comment',
					'defaults' => [
						'controller' => Controller\CommentController::class,
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
								'action' => 'detail|delete|reply|comments',
							]
						],
						'may_terminate' => true,
						'child_routes' => [
							'json' => [
								'type' => 'Literal',
								'options' => [
									'route' => '/json',
									'defaults' => [
										'controller' => Controller\Js\CommentController::class,
									],
								],
							]
						],
					],
				],
			],
			'note' => [
				'type' => 'Literal',
				'options' => [
					'route' => '/note',
					'defaults' => [
						'controller' => Controller\NoteController::class,
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
								'action' => 'detail|edit|delete|comments|comment',
							]
						],
						'may_terminate' => true,
						'child_routes' => [
							'json' => [
								'type' => 'Literal',
								'options' => [
									'route' => '/json',
									'defaults' => [
										'controller' => Controller\Js\NoteController::class,
									],
								],
							]
						],
					],
				],
			],
			'version' => [
				'type' => 'Literal',
				'options' => [
					'route' => '/version',
					'defaults' => [
						'controller' => Controller\VersionController::class,
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
								'action' => 'detail|edit|delete|clone|stages|stage|pdf|excel|comments|comment',
							]
						],
						'may_terminate' => true,
						'child_routes' => [
							'json' => [
								'type' => 'Literal',
								'options' => [
									'route' => '/json',
									'defaults' => [
										'controller' => Controller\Js\VersionController::class,
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
								'controller' => Controller\VersionTypeController::class,
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
										'controller' => Controller\Js\VersionTypeController::class,
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
												'controller' => Controller\Js\VersionTypeController::class,
											],
										],
									]
								],
							],
						],
					],
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
								'action' => 'detail|edit|delete|image|hints|hint|comments|comment',
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
			'plant' => [
				'type' => 'Literal',
				'options' => [
					'route' => '/plant',
					'defaults' => [
						'controller' => Controller\PlantController::class,
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
										'controller' => Controller\Js\PlantController::class,
									]
								],
							]
						],
					],
					'json' => [
						'type' => 'Literal',
						'options' => [
							'route' => '/json',
							'defaults' => [
								'controller' => Controller\Js\PlantController::class,
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
						],
					],
				],
			],
			'complexity' => [
				'type' => 'Literal',
				'options' => [
					'route' => '/complexity',
					'defaults' => [
						'controller' => Controller\ComplexityController::class,
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
										'controller' => Controller\Js\ComplexityController::class,
									]
								],
							]
						],
					],
					'json' => [
						'type' => 'Literal',
						'options' => [
							'route' => '/json',
							'defaults' => [
								'controller' => Controller\Js\ComplexityController::class,
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
						],
					],
				],
			],
			'machine' => [
				'type' => 'Literal',
				'options' => [
					'route' => '/machine',
					'defaults' => [
						'controller' => Controller\MachineController::class,
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
										'controller' => Controller\Js\MachineController::class,
									]
								],
							]
						],
					],
					'json' => [
						'type' => 'Literal',
						'options' => [
							'route' => '/json',
							'defaults' => [
								'controller' => Controller\Js\MachineController::class,
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
					//'add' => [
					//	'type' => 'Literal',
					//	'options' => [
					//		'route' => '/add',
					//		'defaults' => [
					//			'action' => 'add' 
					//		]
					//	],
					//],
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
										'controller' => Controller\Js\MaterialController::class,
									]
								],
							],
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
								'action' => 'detail|edit|delete|replace|stages|hints|hint|actions',
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
								]
							],
							//'add' => [
							//	'type' => 'Literal',
							//	'options' => [
							//		'route' => '/add',
							//		'defaults' => [
							//			'action' => 'add' 
							//		]
							//	],
							//],
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
					'json' => [
						'type' => 'Literal',
						'options' => [
							'route' => '/json',
							'defaults' => [
								'controller' => Controller\Js\HintController::class,
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
								'action' => 'detail|edit|delete|context|reason|comments|comment|actions',
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
										'action' => 'detail|edit|delete|hints',
									]
								],
								'may_terminate' => true,
								'child_routes' => [
									'json' => [
										'type' => 'Literal',
										'options' => [
											'route' => '/json',
											'defaults' => [
												'controller' => Controller\Js\HintTypeController::class,
											],
										],
									]
								],
							],
						],
					],
					'reason' => [
						'type' => 'Literal',
						'options' => [
							'route' => '/reason',
							'defaults' => [
								'controller' => Controller\HintReasonController::class,
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
										'action' => 'detail|edit|delete|note|relation|influence|comments|comment',
									]
								],
								'may_terminate' => true,
								'child_routes' => [
									'json' => [
										'type' => 'Literal',
										'options' => [
											'route' => '/json',
											'defaults' => [
												'controller' => Controller\Js\HintReasonController::class,
											],
										],
									]
								],
							],
						],
					],
					'influence' => [
						'type' => 'Literal',
						'options' => [
							'route' => '/influence',
							'defaults' => [
								'controller' => Controller\HintInfluenceController::class,
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
										'action' => 'detail|edit|delete|note|relation|simulation|comments|comment',
									]
								],
								'may_terminate' => true,
								'child_routes' => [
									'json' => [
										'type' => 'Literal',
										'options' => [
											'route' => '/json',
											'defaults' => [
												'controller' => Controller\Js\HintInfluenceController::class,
											],
										],
									]
								],
							],
						],
					],
					'context' => [
						'type' => 'Literal',
						'options' => [
							'route' => '/context',
							'defaults' => [
								'controller' => Controller\HintContextController::class,
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
										'action' => 'detail|edit|delete|reason|relation|influence|simulate|comments|comment',
									]
								],
								'may_terminate' => true,
								'child_routes' => [
									'json' => [
										'type' => 'Literal',
										'options' => [
											'route' => '/json',
											'defaults' => [
												'controller' => Controller\Js\HintContextController::class,
											],
										],
									]
								],
							],
						],
					],
					'relation' => [
						'type' => 'Literal',
						'options' => [
							'route' => '/relation',
							'defaults' => [
								'controller' => Controller\HintRelationController::class,
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
										'action' => 'detail|edit|delete|comments|comment',
									]
								],
								'may_terminate' => true,
								'child_routes' => [
									'json' => [
										'type' => 'Literal',
										'options' => [
											'route' => '/json',
											'defaults' => [
												'controller' => Controller\Js\HintRelationController::class,
											],
										],
									]
								],
							],
						],
					],
					'simulation' => [
						'type' => 'Literal',
						'options' => [
							'route' => '/simulation',
							'defaults' => [
								'controller' => Controller\Js\SimulationController::class,
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
										'controller' => Controller\Js\SimulationController::class,
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
										'action' => 'detail|edit|delete|render|suggestion|effect|prevention|comments|comment',
									]
								],
								'may_terminate' => true,
								'child_routes' => [
									'json' => [
										'type' => 'Literal',
										'options' => [
											'route' => '/json',
											'defaults' => [
											'controller' => Controller\Js\SimulationController::class,
											],
										],
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
