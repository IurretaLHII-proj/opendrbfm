<?php

namespace MA;

return [
	'default' => [
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
		[
			'label' => 'Customer',
			'route' => 'customer',
			'class' => 'nav-link',
			'resource'	=> Entity\Customer::class,
			'privilege' => 'add',
			'pages' => [
				[
					'label' => 'Add Customer',
					'route' => 'customer/add',
					'class' => 'nav-link d-none',
					'resource'	=> Entity\Customer::class,
					'privilege' => 'add',
				],
			],
		],
		[
			'label' => 'User',
			'route' => 'user/list',
			'class' => 'nav-link',
			'resource'	=> Entity\User::class,
			'privilege' => 'add',
			'pages' => [
				[
					'label' => 'Add User',
					'route' => 'user/add',
					'class' => 'nav-link d-none',
					'resource'	=> Entity\User::class,
					'privilege' => 'add',
				],
			],
		],
	],
];
