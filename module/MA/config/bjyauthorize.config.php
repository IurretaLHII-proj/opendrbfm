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
                	Entity\User::ROLE_ADMIN => [],
            	]
			],
        ],
    ],
    /*'resource_providers' => [
        \BjyAuthorize\Provider\Resource\Config::class => [
			Entity\User::class,
			Entity\Issue::class,
			Entity\Issue\Image::class,
		]
	],
    'rule_providers' => [
        \BjyAuthorize\Provider\Rule\Config::class => [
            'allow' => [
				[
					[Entity\User::ROLE_GUEST, Entity\User::ROLE_USER],
					[],
					['detail'],
				],
				[
					Entity\User::ROLE_USER, 
					[],
					['edit'],
					'IsOwner',
				],
				[
					Entity\User::ROLE_ADMIN, 
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
	],*/
];
