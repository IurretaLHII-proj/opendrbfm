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
			'max_depth' => 2,
		],
		'MA\Entity\Process' => [
			'route_name' => 'process/detail',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 3,
			'hydrator' => Hydrator\ProcessHydrator::class,
		],
		'MA\Entity\Version' => [
			'route_name' => 'process/version/detail/json',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 2,
		],
		'MA\Entity\VersionType' => [
			'route_name' => 'process/version/type/detail',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 2,
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
			'max_depth' => 9,
			'hydrator' => Hydrator\HintHydrator::class,
		],
		'MA\Entity\HintType' => [
			'route_name' => 'process/hint/type/detail',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 2,
		],
		'MA\Entity\HintReason' => [
			'route_name' => 'process/hint/reason/detail',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 8,
		],
		'MA\Entity\HintInfluence' => [
			'route_name' => 'process/hint/influence/detail',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 6,
		],
		'MA\Entity\HintContext' => [
			'route_name' => 'process/hint/context/detail',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 5,
		],
		'MA\Entity\HintRelation' => [
			'route_name' => 'process/hint/relation/detail',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 4,
		],
		'MA\Entity\HintContextRel' => [
			'route_name' => 'process/hint/context/detail',//TODO
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 1,
		],
		'MA\Entity\HintReasonRel' => [
			'route_name' => 'process/hint/reason/detail',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 1,
		],
		'MA\Entity\HintInfluenceRel' => [
			'route_name' => 'process/hint/influence/detail',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 1,
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
		'MA\Entity\Note\HintInfluence' => [
			'route_name' => 'process/note/detail', //FIXME
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 2,
		],
		'MA\Entity\Note\HintEffect' => [
			'route_name' => 'process/note/detail', //FIXME
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 2,
		],
		'MA\Entity\Note\HintPrevention' => [
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
		'MA\Entity\Note\HintReason' => [
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
		'MA\Entity\Image\ISimulation' => [
			'route_name' => 'image/detail',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 0,
		],
		'MA\Entity\Action\Note' => [
			'route_name' => 'process/detail', //FIXME
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 3,
		],
		'MA\Entity\Action\Process' => [
			'route_name' => 'process/detail', //FIXME
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 3,
		],
		'MA\Entity\Action\Version' => [
			'route_name' => 'process/detail', //FIXME
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 3,
		],
		'MA\Entity\Action\Stage' => [
			'route_name' => 'process/detail', //FIXME
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 3,
		],
		'MA\Entity\Action\Hint' => [
			'route_name' => 'process/detail', //FIXME
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 3,
		],
		'MA\Entity\Action\HintReason' => [
			'route_name' => 'process/detail', //FIXME
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 3,
		],
		'MA\Entity\Action\HintInfluence' => [
			'route_name' => 'process/detail', //FIXME
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 3,
		],
		'MA\Entity\Action\Simulation' => [
			'route_name' => 'process/detail', //FIXME
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 3,
		],
		'MA\Entity\Action\Comment' => [
			'route_name' => 'process/detail', //FIXME
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 3,
		],
		'MA\Entity\Notification' => [
			'route_name' => 'process/notification',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 3,
		],
		'MA\Entity\Comment\Version' => [
			'route_name' => 'process/comment/detail/json', 
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 2,
		],
		'MA\Entity\Comment\Stage' => [
			'route_name' => 'process/comment/detail/json', 
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 2,
		],
		'MA\Entity\Comment\Hint' => [
			'route_name' => 'process/comment/detail/json', 
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 2,
		],
		'MA\Entity\Comment\HintReason' => [
			'route_name' => 'process/comment/detail/json', 
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 2,
		],
		'MA\Entity\Comment\HintInfluence' => [
			'route_name' => 'process/comment/detail/json', 
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 2,
		],
		'MA\Entity\Comment\HintContext' => [
			'route_name' => 'process/comment/detail/json', 
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 2,
		],
		'MA\Entity\Comment\HintRelation' => [
			'route_name' => 'process/comment/detail/json', 
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 2,
		],
		'MA\Entity\Comment\Simulation' => [
			'route_name' => 'process/comment/detail/json', 
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 2,
		],
		'MA\Entity\Comment\Note' => [
			'route_name' => 'process/comment/detail/json', 
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 2,
		],
		'MA\Entity\Complexity' => [
			'route_name' => 'process/complexity/detail',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 1,
		],
		'MA\Entity\Machine' => [
			'route_name' => 'process/machine/detail',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 1,
		],
		'MA\Entity\ProductivePlant' => [
			'route_name' => 'process/plant/detail',
			'route_identifier_name' => 'id',
			'entity_identifier_name' => 'id',
			'route_params' => ['action' => 'detail'],
			'max_depth' => 1,
		],
	],
];
