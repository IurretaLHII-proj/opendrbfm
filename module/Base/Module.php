<?php

namespace Base;

use Zend\Mvc\MvcEvent;

class Module
{
	/**
	 * @return array
	 */
    public function onBootstrap(MvcEvent $e)
    {
        $sm = $e->getApplication()->getServiceManager();
        $ev = $e->getApplication()->getEventManager();

		$sm->get(Controller\Service\Navigation::class)->attachServices($ev->getSharedManager());

		$auth = $sm->get('BjyAuthorize\Service\Authorize');
		\Zend\Navigation\Page\Mvc::setDefaultRouter($sm->get('router'));
		\Zend\View\Helper\Navigation\Menu::setDefaultAcl($auth->getAcl());
		\Zend\View\Helper\Navigation\Menu::setDefaultRole($auth->getIdentity());

		$ev->getSharedManager()->attachAggregate(new Hal\LinkPrepareSharedListener($auth));
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
		return [
			'abstract_factories' => [
				Factory\Controller\ControllerFactory::class,
			],
		];
	}

	/**
	 * @return array
	 */
    public function getServiceConfig()
	{
		return [
			'invokables' => [
				'CollectionExtract' => Hydrator\Strategy\CollectionExtract::class
			],
			'factories' => [
				'navigation' => \Zend\Navigation\Service\DefaultNavigationFactory::class,
				Controller\Service\Navigation::class => Factory\Controller\Service\NavigationFactory::class, 
			],
			'abstract_factories' => [
				Factory\Service\ServiceFactory::class,
			],
		];
	}

	/**
	 * @return array
	 */
    public function getFormElementConfig()
    {
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
	}
}
