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
					'label' => 'New',
					'route' => 'process/add',
					'class' => 'nav-link d-none',
					'resource'	=> Entity\Process::class,
					'privilege' => 'add',
				],
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
						],
					],
				],
				[
					'label' => 'Materials',
					'route' => 'process/material',
					'class' => 'nav-link',
					'resource'	=> Entity\Material::class,
					'privilege' => 'index',
					//'pages' => [
					//	[
					//		'label' => 'Add Material',
					//		'route' => 'process/material/add',
					//		'class' => 'nav-link d-none',
					//		'resource'	=> Entity\Material::class,
					//		'privilege' => 'add',
					//	],
					//],
				],
			],
		],
	],
];
