<?php

namespace Base\Factory\Controller;

use Zend\ServiceManager\AbstractFactoryInterface,
	Zend\ServiceManager\ServiceLocatorInterface,
	Zend\ServiceManager\AbstractPluginManager,
	Zend\ServiceManager\ServiceManager;

use Base\Controller\AbstractActionController;

class ControllerFactory implements AbstractFactoryInterface
{
	/**
	 * @inheritDoc
	 */
	public function canCreateServiceWithName(ServiceLocatorInterface $sm, $name, $className)
	{
		if ($sm instanceof AbstractPluginManager) {
			$sm = $sm->getServiceLocator();
		}

		$config = $sm->get('config');

		if (!isset($config['base_controllers'])) {
			return false;
		}

		$config = $config['base_controllers'];

		return isset($config[$className]);
	}

	/**
	 * @inheritDoc
	 */
	public function createServiceWithName(ServiceLocatorInterface $sm, $name, $className)
	{
		if ($sm instanceof AbstractPluginManager) {
			$sm = $sm->getServiceLocator();
		}

		$config = $sm->get('config');

		if (!class_exists($className)) {
			throw new \InvalidArgumentException(sprintf("Controller class not exists: %s", $className));
		}

		$controller = new $className;

		if (!$controller instanceof AbstractActionController) {
			throw new \InvalidArgumentException(sprintf("%s controller must implement: %s", 
				get_class($controller), AbstractActionController::class));
		}

		$navService = $sm->get(\Base\Controller\Service\Navigation::class);

		$controller->setNavService($navService);

		$config = $config['base_controllers'][$className];

		if (isset($config['service'])) {
			try {
				$controller->setService($sm->get($config['service']));
			}
			catch (\Exception $e) {
				throw new \RuntimeException(sprintf("cannot retrieve %s service", $config['service']));
			}
		}

		return $controller;
	}
}
