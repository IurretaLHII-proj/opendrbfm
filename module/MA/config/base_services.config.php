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
					Service\NoteService::class, 
					Service\CustomerService::class, 
					Service\MaterialService::class, 
					Service\ProcessService::class, 
					Service\StageService::class, 
					Service\HintTypeService::class, 
					Service\HintService::class, 
					Service\SimulationService::class, 
					Service\ImageService::class, 
					Service\OperationService::class, 
					Service\OperationTypeService::class, 
				],
				'events'   => \Base\Service\AbstractService::EVENT_CREATE, 
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
	Service\SimulationService::class => [
		'entities' => [Entity\Simulation::class],
	],
	Service\ImageService::class => [
		'entities' => [Entity\Image\IStage::class],
	],
	Service\NoteService::class => [
		'entities'  => [
			Entity\Note\HintReason::class, 
			Entity\Note\HintSuggestion::class, 
			Entity\Note\HintInfluence::class, 
		],
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
