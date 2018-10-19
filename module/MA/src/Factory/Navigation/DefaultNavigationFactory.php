<?php

namespace MA\Factory\Navigation;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Navigation\Service\DefaultNavigationFactory as BaseFactory;
use Interop\Container\ContainerInterface;

/**
 * Default navigation factory.
 */
class DefaultNavigationFactory extends BaseFactory
{
	/**
	 * @var Zend\Authentication\AuthenticationService
	 */
	protected $authService;

	/**
	 * @inheritDoc
	 */
    public function createService(ServiceLocatorInterface $container, $nm = null, $rqName = null)
    {
		$this->authService = $container->get("ZfcUserAuthService");
		return parent::createService($container, $nm, $rqName);
    }

	/**
	 * @inheritDoc
	 */
	protected function getPages(ContainerInterface $container)
	{
		$this->pages = parent::getPages($container);

        if (null !== ($identity = $this->authService->getIdentity())) {

			$config = [
				[
					'label' => $identity->getName(),
					'route' => 'user/detail',
					'class' => 'nav-link',
					'resource'	=> \MA\Entity\User::class,
					'privilege' => 'detail',
					'params' => ['id' => $identity->getId()],
					'pages' => [
						[
							'label' => 'logout',
							'route' => 'zfcuser/logout',
							'class' => 'nav-link',
							'resource'	=> \MA\Entity\User::class,
							'privilege' => 'logout',
						],
					],
				],
			];

			foreach ($this->preparePages($container, $config) as $page) {
				$this->pages[] = $page;	
			}
		}

		return $this->pages;	
	}
}
