<?php

namespace Issues;

return [
	'default' => [
		//[
		//	'label' => 'Home',
		//	'route' => 'home',
		//	'class' => 'nav-item',
		//],
		[
			'label' => 'Process',
			'route' => 'home',
			'class' => 'nav-link',
			//'resource'	=> Entity\Process::class,
			//'privilege' => 'add',
			'pages' => [
				[
					'label' => 'New Process',
					'route' => 'process/add',
					'class' => 'nav-link',
				],
			],
		],
		[
			'label' => 'New User',
			'route' => 'user/add',
			'class' => 'nav-link',
			//'resource'	=> Entity\Process::class,
			//'privilege' => 'add',
		],
	],
];
