<?php

namespace Base\Factory\Controller\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

use Base\Controller\Service\Navigation;

class NavigationFactory implements FactoryInterface
{
	/**
	 * @inheritDc
	 */
	public function createService(ServiceLocatorInterface $sm)
	{
		$config = $sm->get('config');

		$ctrls = isset($config['base_controllers']) ? $config['base_controllers'] : [];
		$svces = isset($config['base_services']) ? $config['base_services'] : [];

		return new Navigation($sm, $ctrls, $svces);
	}
}
