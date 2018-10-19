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
				Form\ProcessForm::class => Form\ProcessForm::class,
				Form\StageForm::class => Form\StageForm::class,
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
				Hydrator\ProcessHydrator::class => Hydrator\ProcessHydrator::class,
				Hydrator\StageHydrator::class => Hydrator\StageHydrator::class,
				Hydrator\HintHydrator::class => Hydrator\HintHydrator::class,
			],
		];
	}

	/**
	 * @return array
	 */
    public function getServiceConfig()
	{
		return [
			'factories' => [
				'navigation' => Factory\Navigation\DefaultNavigationFactory::class,
			],
		];
	}
}
