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
		'MA\Entity\Customer' => [
			'route_name' => 'customer/detail',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 0,
		],
		'MA\Entity\Material' => [
			'route_name' => 'process/material/detail',
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
			'max_depth' => 4,
			'hydrator' => Hydrator\ProcessHydrator::class,
		],
		'MA\Entity\Stage' => [
			'route_name' => 'process/stage/detail',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 2,
			'hydrator' => Hydrator\StageHydrator::class,
		],
		'MA\Entity\OperationType' => [
			'route_name' => 'process/operation/type/detail/json',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 4,
		],
		'MA\Entity\Operation' => [
			'route_name' => 'process/operation/detail',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 2,
		],
		'MA\Entity\Hint' => [
			'route_name' => 'process/hint/detail',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 5,
			'hydrator' => Hydrator\HintHydrator::class,
		],
		'MA\Entity\HintType' => [
			'route_name' => 'process/hint/type/detail',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 2,
		],
		'MA\Entity\Simulation' => [
			'route_name' => 'process/hint/simulation/detail/json',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 3,
		],
		'MA\Entity\Note\HintReason' => [
			'route_name' => 'process/note/detail', //FIXME
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 2,
		],
		'MA\Entity\Note\HintSuggestion' => [
			'route_name' => 'process/note/detail', //FIXME
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 2,
		],
		'MA\Entity\Note\HintInfluence' => [
			'route_name' => 'process/note/detail', //FIXME
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 2,
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
		'MA\Entity\Comment\Hint' => [
			'route_name' => 'process/comment/detail', 
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 1,
		],
		'MA\Entity\Comment\Simulation' => [
			'route_name' => 'process/comment/detail', 
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 1,
		],
		'MA\Entity\Comment\Note' => [
			'route_name' => 'process/comment/detail', 
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 1,
		],
	],
];
