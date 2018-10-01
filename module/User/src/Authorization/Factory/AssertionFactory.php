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
		if (!class_exists($className)) {
			return false;
		}

		if ($sm instanceof AbstractPluginManager) {
			$sm = $sm->getServiceLocator();
		}

		$assertion = new $className($this->getIdentity($sm));

		return $assertion instanceof \User\Authorization\Assertion\AssertionInterface;
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
