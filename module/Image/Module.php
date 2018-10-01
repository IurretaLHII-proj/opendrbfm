<?php

namespace Image;

use Zend\Mvc\MvcEvent;

class Module
{
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
    public function getFormElementConfig()
    {
		return [
			'invokables' => [
				Form\ImageFieldset::class => Form\ImageFieldset::class,
				Form\ImageUploadForm::class => Form\ImageUploadForm::class,
			],
		];
    }

	/**
	 * @return array
	 */
    public function getInputFilterConfig()
    {
		return [
			'invokables' => [
				InputFilter\ImageInputFilter::class => InputFilter\ImageInputFilter::class,
				InputFilter\ImageUploadInputFilter::class => InputFilter\ImageUploadInputFilter::class,
			],
		];
    }

	/**
	 * @return array
	 */
	public function getHydratorConfig()
	{
		return [
			'invokables' => [
				Hydrator\ImageHydrator::class => Hydrator\ImageHydrator::class,
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
