<?php

namespace User;

use Zend\Mvc\MvcEvent;

class Module
{
	/**
	 * @return array
	 */
    public function onBootstrap(MvcEvent $e)
    {
		$sm  = $e->getTarget()->getServiceManager();
		$acl = $sm->get('BjyAuthorize\Service\Authorize')->getAcl();;
		
		//$acl->addRole('user');
    }

	/**
	 * @return array
	 */
    public function getAutoloaderConfig()
    {
        return [ 
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/',
                ],
            ],
        ];
    }

	/**
	 * @return array
	 */
    public function getConfig()
	{
		return [
			'user_assertions' => [
				Authorization\Assertion\IsOwner::class,
			]
		];
	}

	/**
	 * @return array
	 */
    public function getServiceConfig()
	{
		return [
			'aliases' => [
				'isOwner' => Authorization\Assertion\IsOwner::class, 
			],
			'abstract_factories' => [
				Authorization\Factory\AssertionFactory::class,
			],
		];
	}
}
