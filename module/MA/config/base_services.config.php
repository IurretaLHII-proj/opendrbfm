<?php

namespace MA;

return [
	Service\UserService::class => [
		'entities' => [Entity\User::class],
		'listeners' => [
			[
				'services' => [
					Service\ActionService::class, 
					Service\ActionProcessService::class, 
					Service\CommentService::class, 
					Service\NoteService::class, 
					Service\CustomerService::class, 
					Service\MaterialService::class, 
					Service\ProcessService::class, 
					Service\VersionService::class, 
					Service\StageService::class, 
					Service\HintTypeService::class, 
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
	Service\ProcessService::class => [
		'entities' => [Entity\Process::class],
	],
	Service\VersionService::class => [
		'entities' => [Entity\Version::class],
	],
	Service\StageService::class => [
		'entities' => [Entity\Stage::class],
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
					Service\SimulationService::EVENT_UPDATE, 
				],
				'callback' => 'createSimulation',
				'priority' => 100,
			]
		],
	],
	Service\ImageService::class => [
		'entities' => [Entity\Image\IStage::class],
	],
	Service\NoteService::class => [
		'entities'  => [
			Entity\Note\ContextReason::class, 
			Entity\Note\ContextInfluence::class, 
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
		]
	],
	Service\ActionService::class => [
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
	Service\ActionProcessService::class => [
		//'entities'  => [
		//	Entity\Action\Process::class, 
		//	Entity\Action\Stage::class, 
		//	Entity\Action\Hint::class, 
		//],
		//'listeners' => [
		//	[
		//		'services' => [
		//			Service\ProcessService::class, 
		//			Service\StageService::class, 
		//			Service\HintService::class
		//		],
		//		'events'   => ['*'], 
		//		'callback' => 'createAction',
		//	]
		//],
	],
];
