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
        $sm        = $e->getApplication()->getServiceManager();
        $ev        = $e->getApplication()->getEventManager();

		$hEvs = [\Zend\Mvc\MvcEvent::EVENT_DISPATCH_ERROR, \Zend\Mvc\MvcEvent::EVENT_RENDER_ERROR]; 

    	//handle the dispatch error (exception) 
    	//handle the view render error (exception) 
    	//$ev->attach($hEvs, function($ev) { die($ev->getParam('exception')); });
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
			],
			'factories' => [
				/*Form\SimulationFieldset::class => function($sm) {
					$filter = $sm->getServiceLocator()
						->get('InputFilterManager')
						->get(InputFilter\SimulationInputFilter::class);

					$form = new Form\SimulationFieldset;
					$form->setInputFilter($filter);
					return $form;	
				}*/
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
				InputFilter\SimunaltionInputFilter::class => InputFilter\SimulationInputFilter::class,
			],
			'factories' => [
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
