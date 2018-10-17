<?php

namespace MA;

return [
	Controller\UserController::class => [
		'repository' => Entity\User::class,
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
	Controller\HintController::class => [
		'repository' => Entity\Hint::class,
	],
	Controller\Js\HintController::class => [
		'repository' => Entity\Hint::class,
	],
	Controller\ImageController::class => [
		'repository' => Entity\Image\IStage::class,
	],
];
