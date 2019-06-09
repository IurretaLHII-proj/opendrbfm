<?php

namespace MA;

return [
	'default' => [
		[
			'label' => 'Process',
			'route' => 'process',
			'class' => 'nav-link',
			'resource'	=> Entity\Process::class,
			'privilege' => 'index',
			'pages' => [
				//[
				//	'label' => 'New',
				//	'route' => 'process/add',
				//	'class' => 'nav-link d-none',
				//	'resource'	=> Entity\Process::class,
				//	'privilege' => 'add',
				//],
				[
					'label' => 'Template',
					'route' => 'process/tpl',
					'class' => 'nav-link',
					'resource'	=> Entity\Process::class,
					'privilege' => 'tpl',
				],
				[
					'label' => 'Operation type',
					'route' => 'process/operation/type',
					'class' => 'nav-link',
					'resource'	=> Entity\OperationType::class,
					'privilege' => 'index',
					'pages' => [
						//[
						//	'label' => 'Add Operation type',
						//	'route' => 'process/operation/type/add',
						//	'class' => 'nav-link d-none',
						//	'resource'	=> Entity\OperationType::class,
						//	'privilege' => 'add',
						//],
					],
				],
				[
					'label' => 'Version type',
					'route' => 'process/version/type',
					'class' => 'nav-link',
					'resource'	=> Entity\VersionType::class,
					'privilege' => 'index',
					'pages' => [
						//[
						//	'label' => 'Add version type',
						//	'route' => 'process/version/type/add',
						//	'class' => 'nav-link d-none',
						//	'resource'	=> Entity\VersionType::class,
						//	'privilege' => 'add',
						//],
					],
				],
				[
					'label' => 'Productive plant',
					'route' => 'process/plant',
					'class' => 'nav-link',
					'resource'	=> Entity\ProductivePlant::class,
					'privilege' => 'index',
				],
				[
					'label' => 'Machine',
					'route' => 'process/machine',
					'class' => 'nav-link',
					'resource'	=> Entity\Machine::class,
					'privilege' => 'index',
				],
				[
					'label' => 'Complexity',
					'route' => 'process/complexity',
					'class' => 'nav-link',
					'resource'	=> Entity\Complexity::class,
					'privilege' => 'index',
				],
				[
					'label' => 'Material',
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
			'label' => 'Error',
			'route' => 'process/hint',
			'class' => 'nav-link',
			'resource'	=> Entity\Hint::class,
			'privilege' => 'index',
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
