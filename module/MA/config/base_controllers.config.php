<?php

namespace MA;

return [
	Controller\UserController::class => [
		'repository' => Entity\User::class,
	],
	Controller\Js\UserController::class => [
		'repository' => Entity\User::class,
	],
	Controller\CustomerController::class => [
		'repository' => Entity\Customer::class,
	],
	Controller\ProcessController::class => [
		'repository' => Entity\Process::class,
		'parent' => [
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
		],
	],
	Controller\Js\ProcessController::class => [
		'repository' => Entity\Process::class,
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
	Controller\HintController::class => [
		'repository' => Entity\Hint::class,
		'parent' => [
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
		],
	],
	Controller\Js\HintController::class => [
		'repository' => Entity\Hint::class,
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
