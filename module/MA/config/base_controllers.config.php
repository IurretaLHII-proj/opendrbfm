<?php

namespace MA;

return [
	Controller\UserController::class => [
		'repository' => Entity\User::class,
	],
	Controller\Js\UserController::class => [
		'repository' => Entity\User::class,
	],
	Controller\Js\NoteController::class => [
		'repository' => Entity\AbstractNote::class,
	],
	Controller\Js\CommentController::class => [
		'repository' => Entity\AbstractComment::class,
	],
	Controller\Js\Comment\VersionController::class => [
		'repository' => Entity\Comment\Version::class,
	],
	Controller\Js\Comment\StageController::class => [
		'repository' => Entity\Comment\Stage::class,
	],
	Controller\Js\Comment\HintController::class => [
		'repository' => Entity\Comment\Hint::class,
	],
	Controller\Js\Comment\HintReasonController::class => [
		'repository' => Entity\Comment\HintReason::class,
	],
	Controller\Js\Comment\HintInfluenceController::class => [
		'repository' => Entity\Comment\HintInfluence::class,
	],
	Controller\Js\Comment\HintContextController::class => [
		'repository' => Entity\Comment\HintContext::class,
	],
	Controller\Js\Comment\HintRelationController::class => [
		'repository' => Entity\Comment\HintRelation::class,
	],
	Controller\Js\Comment\NoteController::class => [
		'repository' => Entity\Comment\Note::class,
	],
	Controller\Js\Comment\SimulationController::class => [
		'repository' => Entity\Comment\Simulation::class,
	],
	Controller\CustomerController::class => [
		'repository' => Entity\Customer::class,
	],
	Controller\Js\CustomerController::class => [
		'repository' => Entity\Customer::class,
	],
	Controller\ProcessController::class => [
		'repository' => Entity\Process::class,
		/*'parent' => [
			'route' => 'home',
		],
		'page' => [
			'route' => 'process/detail',
			'action' => 'detail',
			'pages' => [
				[
					'route' => 'process/detail',
					'action' => 'detail',
				],
				[
					'route' => 'process/detail',
					'action' => 'actions',
				],
			]
		],*/
	],
	Controller\Js\ProcessController::class => [
		'repository' => Entity\Process::class,
	],
	Controller\Js\VersionController::class => [
		'repository' => Entity\Version::class,
	],
	Controller\Js\StageController::class => [
		'repository' => Entity\Stage::class,
	],
	Controller\MaterialController::class => [
		'repository' => Entity\Material::class,
	],
	Controller\Js\MaterialController::class => [
		'repository' => Entity\Material::class,
	],
	Controller\OperationController::class => [
		'repository' => Entity\Operation::class,
	],
	Controller\Js\OperationController::class => [
		'repository' => Entity\Operation::class,
	],
	Controller\OperationTypeController::class => [
		'repository' => Entity\OperationType::class,
	],
	Controller\Js\OperationTypeController::class => [
		'repository' => Entity\OperationType::class,
	],
	Controller\HintTypeController::class => [
		'repository' => Entity\HintType::class,
	],
	Controller\Js\HintTypeController::class => [
		'repository' => Entity\HintType::class,
	],
	Controller\HintController::class => [
		'repository' => Entity\Hint::class,
		/*'parent' => [
			'controller' => Controller\ProcessController::class,
			'route' => 'process/detail',
			'action' => 'detail',
			'entity' => 'process',
		],
		'page' => [
			'route' => 'process/hint/detail',
			'action' => 'detail',
			'pages' => [
				[
					'route' => 'process/hint/detail',
					'action' => 'detail',
				],
				[
					'route' => 'process/hint/detail',
					'action' => 'actions',
				],
			]
		],*/
	],
	Controller\Js\HintController::class => [
		'repository' => Entity\Hint::class,
	],
	Controller\Js\HintReasonController::class => [
		'repository' => Entity\HintReason::class,
	],
	Controller\Js\HintInfluenceController::class => [
		'repository' => Entity\HintInfluence::class,
	],
	Controller\Js\HintContextController::class => [
		'repository' => Entity\HintContext::class,
	],
	Controller\Js\HintRelationController::class => [
		'repository' => Entity\HintRelation::class,
	],
	Controller\SimulationController::class => [
		'repository' => Entity\Simulation::class,
	],
	Controller\Js\SimulationController::class => [
		'repository' => Entity\Simulation::class,
	],
	Controller\ImageController::class => [
		'repository' => Entity\Image\IStage::class,
	],
];
