<?php

namespace MA;

return [
	'default' => [
		[
			'label' => 'Add User',
			'route' => 'user/add',
			'class' => 'nav-link',
			'resource'	=> Entity\User::class,
			'privilege' => 'add',
		],
		[
			'label' => 'Process',
			'route' => 'home',
			'class' => 'nav-link',
			'resource'	=> Entity\Process::class,
			'privilege' => 'index',
			'pages' => [
				[
					'label' => 'Operation types',
					'route' => 'process/operation/type',
					'class' => 'nav-link',
					'resource'	=> Entity\OperationType::class,
					'privilege' => 'index',
					'pages' => [
						[
							'label' => 'Add Operation type',
							'route' => 'process/operation/type/add',
							'class' => 'nav-link d-none',
							'resource'	=> Entity\OperationType::class,
							'privilege' => 'add',
							'visible'	=> false,
						],
					],
				],
				[
					'label' => 'Add Process',
					'route' => 'process/add',
					'class' => 'nav-link',
					'resource'	=> Entity\Process::class,
					'privilege' => 'add',
				],
			],
		],
	],
];
