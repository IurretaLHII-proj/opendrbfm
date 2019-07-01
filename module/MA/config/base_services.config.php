<?php

namespace MA;

return [
	Service\UserService::class => [
		'entities' => [Entity\User::class],
		'listeners' => [
			[
				'services' => [
					//Service\ActionService::class, 
					Service\Action\OperationService::class, 
					Service\Action\ProcessService::class, 
					Service\Action\VersionService::class, 
					Service\Action\StageService::class, 
					Service\Action\HintService::class, 
					Service\Action\HintReasonService::class, 
					Service\Action\HintInfluenceService::class, 
					Service\Action\SimulationService::class, 
					Service\Action\NoteService::class, 
					Service\Action\CommentService::class, 
					Service\CommentService::class, 
					Service\NoteService::class, 
					Service\CustomerService::class, 
					Service\MaterialService::class, 
					Service\ProductivePlantService::class, 
					Service\ComplexityService::class, 
					Service\MachineService::class, 
					Service\ProcessService::class, 
					Service\VersionService::class, 
					Service\VersionTypeService::class, 
					Service\StageService::class, 
					Service\HintTypeService::class, 
					Service\HintReasonService::class, 
					Service\HintInfluenceService::class, 
					Service\HintContextService::class, 
					Service\HintRelationService::class, 
					Service\HintService::class, 
					Service\SimulationService::class, 
					Service\ImageService::class, 
					Service\OperationService::class, 
					Service\OperationTypeService::class, 
				],
				'events'   => [
					\Base\Service\AbstractService::EVENT_CREATE,
					Service\CommentService::EVENT_REPLY,
					Service\ProcessService::EVENT_CLONE,
				],
				'callback' => 'injectUser',
				'priority' => 100,
			],
		]
	],
	Service\CustomerService::class => [
		'entities' => [Entity\Customer::class],
	],
	Service\MaterialService::class => [
		'entities' => [Entity\Material::class],
	],
	Service\ProductivePlantService::class => [
		'entities' => [Entity\ProductivePlant::class],
	],
	Service\ComplexityService::class => [
		'entities' => [Entity\Complexity::class],
	],
	Service\MachineService::class => [
		'entities' => [Entity\Machine::class],
	],
	Service\ProcessService::class => [
		'entities' => [Entity\Process::class],
	],
	Service\VersionTypeService::class => [
		'entities'  => [Entity\VersionType::class],
	],
	Service\VersionService::class => [
		'entities' => [Entity\Version::class],
	],
	Service\StageService::class => [
		'entities' => [Entity\Stage::class],
		'listeners' => [
			[
				'services' => [
					Service\VersionService::class,
				],
				'events'   => [
					Service\VersionService::EVENT_CLONE,
				],
				'callback' => 'cloneStages',
			]
		],
	],
	Service\OperationTypeService::class => [
		'entities' => [Entity\OperationType::class],
	],
	Service\OperationService::class => [
		'entities' => [Entity\Operation::class],
	],
	Service\HintTypeService::class => [
		'entities' => [Entity\HintType::class],
		'listeners' => [
			[
				'services' => Service\HintService::class, 
				'events'   => \Base\Service\AbstractService::EVENT_CREATE, 
				'callback' => 'increaseErrors',
				'priority' => 100,
			],
			[
				'services' => Service\HintService::class, 
				'events'   => \Base\Service\AbstractService::EVENT_REMOVE, 
				'callback' => 'decreaseErrors',
				'priority' => 100,
			],
		],
	],
	Service\HintService::class => [
		'entities' => [Entity\Hint::class],
		'listeners' => [
			[
				'services' => Service\StageService::class,
				'events'   => Service\VersionService::EVENT_CLONE,
				'callback' => 'cloneHints',
			],
		],
	],
	Service\HintRelationService::class => [
		'entities' => [Entity\HintRelation::class],
		'listeners' => [
			[
				'services' => Service\HintRelationService::class, 
				'events'   => \Base\Service\AbstractService::EVENT_CREATE, 
				'callback' => 'createRelation',
				'priority' => 100,
			],
		],
	],
	Service\HintReasonService::class => [
		'entities' => [Entity\HintReason::class],
		'listeners' => [
			[
				'services' => Service\HintReasonService::class, 
				'events'   => [
					Service\HintContextService::EVENT_CREATE, 
					Service\HintContextService::EVENT_UPDATE,
				],
				'callback' => 'createDependencies',
				'priority' => 100,
			],
			[
				'services' => Service\HintService::class,
				'events'   => Service\VersionService::EVENT_CLONE,
				'callback' => 'cloneReasons',
			],
		],
	],
	Service\HintInfluenceService::class => [
		'entities' => [Entity\HintInfluence::class],
		'listeners' => [
			[
				'services' => Service\HintInfluenceService::class, 
				'events'   => [
					Service\HintContextService::EVENT_CREATE, 
					Service\HintContextService::EVENT_UPDATE,
				],
				'callback' => 'createDependencies',
				'priority' => 100,
			],
			[
				'services' => Service\HintReasonService::class,
				'events'   => Service\VersionService::EVENT_CLONE,
				'callback' => 'cloneInfluences',
			],
		],
	],
	Service\HintContextService::class => [
		'entities' => [Entity\HintContext::class],
		'listeners' => [
			[
				'services' => Service\HintContextService::class, 
				'events'   => [
					Service\HintContextService::EVENT_CREATE, 
					Service\HintContextService::EVENT_UPDATE,
				],
				'callback' => 'createContext',
				'priority' => 100,
			]
		],
	],
	Service\SimulationService::class => [
		'entities' => [Entity\Simulation::class],
		'listeners' => [
			[
				'services' => Service\SimulationService::class, 
				'events'   => [
					Service\SimulationService::EVENT_CREATE, 
					//Service\SimulationService::EVENT_UPDATE, 
				],
				'callback' => 'createSimulation',
				'priority' => 100,
			],
			[
				'services' => Service\HintInfluenceService::class,
				'events'   => Service\VersionService::EVENT_CLONE,
				'callback' => 'cloneSimulations',
			],
		],
	],
	Service\ImageService::class => [
		'entities' => [
			Entity\Image\IStage::class,
			Entity\Image\ISimulation::class,
		],
	],
	Service\NoteService::class => [
		'entities'  => [
			Entity\Note\HintSuggestion::class, 
			Entity\Note\HintEffect::class, 
			Entity\Note\HintPrevention::class, 
			Entity\Note\HintReason::class, 
			Entity\Note\HintInfluence::class, 
		],
	],
	Service\CommentService::class => [
		'entities'  => [
			Entity\Comment\Version::class, 
			Entity\Comment\Stage::class, 
			Entity\Comment\Hint::class, 
			Entity\Comment\HintContext::class, 
			Entity\Comment\HintReason::class, 
			Entity\Comment\HintInfluence::class, 
			Entity\Comment\HintRelation::class, 
			Entity\Comment\Note::class, 
			Entity\Comment\Simulation::class, 
		],
		'listeners' => [
			[
				'services' => Service\CommentService::class,
				'events'   => [
					Service\CommentService::EVENT_CREATE,
					Service\CommentService::EVENT_REPLY,
				],
				'callback' => 'increaseCommentCount',
				'priority' => 100,
			],
			[
				'services' => Service\CommentService::class,
				'events'   => [
					Service\CommentService::EVENT_REMOVE,
				],
				'callback' => 'decreaseCommentCount',
				'priority' => 100,
			],
		]
	],
	/*Service\ActionService::class => [
		'entities'  => [
			Entity\Action\Operation::class, 
		],
		'listeners' => [
			[
				'services' => [
					Service\OperationService::class, 
				],
				'events'   => ['*'], 
				'callback' => 'createAction',
			]
		],
	],
	Service\Action\OperationService::class => [
		'entities'  => [
			Entity\Action\Operation::class, 
		],
		'listeners' => [
			[
				'services' => [
					Service\OperationService::class, 
				],
				'events'   => ['*'], 
				'callback' => 'createAction',
			]
		],
	],
	 */
	Service\Action\ProcessService::class => [
		'entities'  => [
			Entity\Action\Process::class, 
		],
		'listeners' => [
			[
				'services' => [
					Service\ProcessService::class, 
				],
				'events'   => ['*'], 
				'callback' => 'createAction',
			]
		],
	],
	Service\Action\VersionService::class => [
		'entities'  => [
			Entity\Action\Version::class, 
		],
		'listeners' => [
			[
				'services' => [
					Service\VersionService::class, 
				],
				'events'   => ['*'], 
				'callback' => 'createAction',
			]
		],
	],
	Service\Action\StageService::class => [
		'entities'  => [
			Entity\Action\Stage::class, 
		],
		'listeners' => [
			[
				'services' => [
					Service\StageService::class, 
				],
				'events'   => ['*'], 
				'callback' => 'createAction',
			]
		],
	],
	Service\Action\HintService::class => [
		'entities'  => [
			Entity\Action\Hint::class, 
		],
		'listeners' => [
			[
				'services' => [
					Service\HintService::class, 
				],
				'events'   => ['*'], 
				'callback' => 'createAction',
			]
		],
	],
	Service\Action\HintReasonService::class => [
		'entities'  => [
			Entity\Action\HintReason::class, 
		],
		'listeners' => [
			//[
			//	'services' => [
			//		Service\HintReasonService::class, 
			//	],
			//	'events'   => ['*'], 
			//	'callback' => 'createAction',
			//]
		],
	],
	Service\Action\HintInfluenceService::class => [
		'entities'  => [
			Entity\Action\HintInfluence::class, 
		],
		'listeners' => [
			//[
			//	'services' => [
			//		Service\HintInfluenceService::class, 
			//	],
			//	'events'   => ['*'], 
			//	'callback' => 'createAction',
			//]
		],
	],
	Service\Action\SimulationService::class => [
		'entities'  => [
			Entity\Action\Simulation::class, 
		],
		'listeners' => [
			[
				'services' => [
					Service\SimulationService::class, 
				],
				'events'   => ['*'], 
				'callback' => 'createAction',
			]
		],
	],
	Service\Action\NoteService::class => [
		'entities'  => [
			Entity\Action\Note::class, 
		],
		'listeners' => [
			[
				'services' => [
					Service\NoteService::class, 
				],
				'events'   => ['*'], 
				'callback' => 'createAction',
			]
		],
	],
	Service\Action\CommentService::class => [
		'entities'  => [
			Entity\Action\Comment::class, 
		],
		'listeners' => [
			[
				'services' => [
					Service\CommentService::class, 
				],
				'events'   => ['*'], 
				'callback' => 'createAction',
			]
		],
	],
	Service\NotificationService::class => [
		'entities'  => [Entity\Notification::class],
		'listeners' => [
			[
				'services' => [Service\Action\CommentService::class],
				'events'   => ['*'], 
				'callback' => 'createNotifications',
			]
		]	
	],
];
