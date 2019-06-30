<?php

namespace User\Authorization\Factory;

use Zend\ServiceManager\AbstractFactoryInterface,
	Zend\ServiceManager\ServiceLocatorInterface,
	Zend\ServiceManager\AbstractPluginManager,
	Zend\ServiceManager\ServiceManager;

use Base\Service\AbstractService;

//FIXME
class AssertionFactory implements AbstractFactoryInterface
{
	protected $identity = false;

	/**
	 * @param ServiceLocatorInterface $sm
	 * @return IdentityInterface|null
	 */
	protected function getIdentity(ServiceLocatorInterface $sm)
	{
		if ($this->identity === false) {
			$this->identity = $sm->get('zfcuser_auth_service')->getIdentity();
		}

		return $this->identity;
	}

	/**
	 * @inheritDoc
	 */
	public function canCreateServiceWithName(ServiceLocatorInterface $sm, $name, $className)
	{
		$config = $sm->get('config');

		if (!(isset($config['user_assertions']) &&
			  is_array($config['user_assertions']) &&
			  in_array($className, $config['user_assertions'])
			)) {
			return false;
		}

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function createServiceWithName(ServiceLocatorInterface $sm, $name, $className)
	{
		if ($sm instanceof AbstractPluginManager) {
			$sm = $sm->getServiceLocator();
		}

		return new $className($this->getIdentity($sm));
	}
}
