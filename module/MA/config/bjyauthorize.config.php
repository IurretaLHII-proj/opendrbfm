<?php

namespace MA;

return [
    'default_role' => Entity\User::ROLE_GUEST,
    'authenticated_role' => Entity\User::ROLE_USER,
    'identity_provider'  => \BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider::class,
    'role_providers' => [
        \BjyAuthorize\Provider\Role\Config::class => [
            Entity\User::ROLE_GUEST => [],
            Entity\User::ROLE_USER => [
				'children' => [
					Entity\User::ROLE_ADMIN => [
						'children' => [
							Entity\User::ROLE_SUPER => [
							],
            			]
					],
            	]
			],
        ],
    ],
    'resource_providers' => [
        \BjyAuthorize\Provider\Resource\Config::class => [
			Entity\User::class,
			Entity\Customer::class,
			Entity\Process::class,
			Entity\Version::class,
			Entity\VersionType::class,
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
		]
	],
    'rule_providers' => [
        \BjyAuthorize\Provider\Rule\Config::class => [
            'allow' => [
				//[
				//	[Entity\User::ROLE_GUEST, Entity\User::ROLE_USER],
				//	[],
				//	['detail'],
				//],
				[
					[Entity\User::ROLE_USER],
					[],
					[
						'index',
						'list',
						'detail',
						'add',
						'actions',
						'pdf',
						'version',
						'comment',
						'comments',
						'reply',
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
						'revention',
						'render',
					],
				],
				[
					Entity\User::ROLE_USER, 
					[],
					['edit', 'delete'],
					'IsOwner',
				],
				[
					[Entity\User::ROLE_ADMIN],
					[],
					[],
				],
            ],
			'deny' => [
				//[
				//	Entity\User::ROLE_GUEST, 
				//	[],
				//	['add', 'edit',]
				//],
			]
		]
	],
];
