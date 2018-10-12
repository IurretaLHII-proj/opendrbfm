<?php

namespace MA;

return [
	'metadata_map' => [
		'MA\Entity\User' => [
			'route_name' => 'user/detail',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 0,
		],
		'MA\Entity\Process' => [
			'route_name' => 'process/detail',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 6,
			'hydrator' => Hydrator\ProcessHydrator::class,
		],
		'MA\Entity\Stage' => [
			'route_name' => 'process/stage/detail',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 4,
			'hydrator' => Hydrator\StageHydrator::class,
		],
		'MA\Entity\Hint' => [
			'route_name' => 'process/hint/detail',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 2,
			'hydrator' => Hydrator\HintHydrator::class,
		],
		'MA\Entity\Image\IStage' => [
			'route_name' => 'image/detail',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 0,
		],
		'MA\Entity\Action\Process' => [
			'route_name' => 'process/detail', //FIXME
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 1,
		],
		'MA\Entity\Action\Stage' => [
			'route_name' => 'process/detail', //FIXME
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 1,
		],
		'MA\Entity\Action\Hint' => [
			'route_name' => 'process/detail', //FIXME
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 1,
		],
	],
];
