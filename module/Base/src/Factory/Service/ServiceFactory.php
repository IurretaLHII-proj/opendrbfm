<?php

namespace Base\Factory\Service;

use Zend\ServiceManager\AbstractFactoryInterface,
	Zend\ServiceManager\ServiceLocatorInterface,
	Zend\ServiceManager\AbstractPluginManager,
	Zend\ServiceManager\ServiceManager;

use Base\Service\AbstractService;

class ServiceFactory implements AbstractFactoryInterface
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

		if (!isset($config['base_services']) && is_array($config['base_services'])) {
			return false;
		}

		return isset($config['base_services'][$className]);
	}

	/**
	 * @inheritDoc
	 */
	public function createServiceWithName(ServiceLocatorInterface $sm, $name, $className)
	{
		if ($sm instanceof AbstractPluginManager) {
			$sm = $sm->getServiceLocator();
		}

		if (!class_exists($className)) {
			throw new \InvalidArgumentException(sprintf("%s service class does not exist", $className));
		}

		$service = new $className;

		if (!$service instanceof AbstractService) {
			throw new \InvalidArgumentException(sprintf("%s service must implement: %s", 
				get_class($service), AbstractService::class));
		}

		$config = $sm->get('config');

		$listeners = isset($config['base_services'][$className]['listeners']) ? 
			$config['base_services'][$className]['listeners'] : [];

		$service->setEventManager($sm->get("EventManager"))
			->setListenersConfig($listeners)
			;

		return $service;
	}
}
