<?php

namespace MA;

$resources = [ 
	Entity\User::class,			
	Entity\Customer::class, 			
	Entity\Process::class,			
	Entity\Version::class,		
	Entity\VersionType::class,
	Entity\Complexity::class,
	Entity\Machine::class,	
	Entity\ProductivePlant::class,
	Entity\Stage::class,			
	Entity\Material::class,		
	Entity\Operation::class,	
	Entity\OperationType::class,
	Entity\Hint::class,			
	Entity\HintType::class,		
	Entity\HintContext::class, 		
	Entity\HintReason::class,	
	Entity\HintInfluence::class, 	
	Entity\HintRelation::class,		
	Entity\AbstractNote::class,		
	Entity\Note\HintReason::class,
	Entity\Note\HintSuggestion::class,
	Entity\Note\HintInfluence::class,
	Entity\Simulation::class,	
	Entity\AbstractComment::class,
	Entity\Comment\Hint::class,
	Entity\Comment\Note::class,     
	Entity\Notification::class,     
];

$privileges = [
	'index', 
	'list',
	'detail',
	'add',
	'edit',
	'delete',
	'read',
	'replace',
	'actions',
	'notifications',
	'pdf',
	'excel',
	'report',
	'version',
	'comment',
	'comments',
	'reply',
	'tpl',
	'clone',
	'stages',
	'stage',
	'image',
	'hint',
	'hints',
	'operation',
	'context',
	'reason',
	'note',
	'relation',
	'influence',
	'simulation',
	'simulate',
	'suggestion',
	'effect',
	'prevention',
	'render',
	'logout',
];

$config = [
    'default_role' => Entity\User::ROLE_GUEST,
    'authenticated_role' => Entity\User::ROLE_USER,
    'identity_provider'  => \BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider::class,
    'role_providers' => [
        \BjyAuthorize\Provider\Role\Config::class => [
            Entity\User::ROLE_GUEST => [],
            Entity\User::ROLE_USER => [
				'children' => [
					Entity\User::ROLE_ADMIN => [
						//'children' => [
						//	Entity\User::ROLE_SUPER => [],
            			//]
					],
            	]
			],
        ],
    ],
    'resource_providers' => [
        \BjyAuthorize\Provider\Resource\Config::class => $resources 
	],
    'rule_providers' => [
        \BjyAuthorize\Provider\Rule\Config::class => [
            'allow' => [
				[
					[Entity\User::ROLE_USER],
					array_filter($resources, function($res) {
						return $res !== \MA\Entity\User::class;
					}),
					array_filter($privileges, function($priv) {
						return !in_array($priv, ['edit', 'delete']);
					}),
				],
				[
					Entity\User::ROLE_USER, 
					[Entity\User::class],
					['detail', 'list', 'index', 'actions', 'notifications', 'logout'],
				],
				[
					Entity\User::ROLE_USER, 
					array_filter($resources, function($res) {
						return $res !== \MA\Entity\User::class;
					}),
					['edit', 'delete'],
					'IsOwner',
				],
				[
					Entity\User::ROLE_ADMIN,
					$resources,
					$privileges,
				],
            ],
			'deny' => [
				//[
				//	Entity\User::ROLE_USER, 
				//	Entity\User::class,
				//	['add']
				//],
			]
		]
	],
];

//var_dump($config);

return $config;
