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
