<?php

namespace MA;

use Zend\Mvc\MvcEvent;

class Module
{
	/**
	 * @return array
	 */
    public function onBootstrap(MvcEvent $e)
    {
    }

	/**
	 * @return array
	 */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
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
    public function getControllerConfig()
	{
	}

	/**
	 * @return array
	 */
    public function getFormElementConfig()
    {
		return [
			'invokables' => [
				//Form\IssueForm::class => Form\IssueForm::class,
			],
		];
    }

	/**
	 * @return array
	 */
    public function getInputFilterConfig()
    {
    }

	/**
	 * @return array
	 */
	public function getHydratorConfig()
	{
		return [
			'invokables' => [
				//Hydrator\IssueHydrator::class => Hydrator\IssueHydrator::class,
			],
		];
	}

	/**
	 * @return array
	 */
    public function getServiceConfig()
	{
	}
}
