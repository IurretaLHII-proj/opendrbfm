<?php

namespace MA;

return [
	Service\UserService::class => [
		'entities' => [Entity\User::class],
		'listeners' => [
			[
				'services' => [
					Service\ActionService::class, 
					Service\ProcessService::class, 
					Service\StageService::class, 
					Service\HintService::class, 
					Service\ImageService::class, 
				],
				'events'   => \Base\Service\AbstractService::EVENT_CREATE, 
				'callback' => 'injectUser',
				'priority' => 100,
			],
		]
	],
	Service\ProcessService::class => [
		'entities' => [Entity\Process::class],
	],
	Service\StageService::class => [
		'entities' => [Entity\Stage::class],
	],
	Service\ImageService::class => [
		'entities' => [Entity\Image\IStage::class],
	],
	Service\HintService::class => [
		'entities' => [Entity\Hint::class],
	],
	Service\ActionService::class => [
		'entities'  => [
			Entity\Action\Process::class, 
			Entity\Action\Stage::class, 
			Entity\Action\Hint::class, 
		],
		'listeners' => [
			[
				'services' => [
					Service\ProcessService::class, 
					Service\StageService::class, 
					Service\HintService::class
				],
				'events'   => ['*'], 
				'callback' => 'createAction',
			]
		],
	],
];
