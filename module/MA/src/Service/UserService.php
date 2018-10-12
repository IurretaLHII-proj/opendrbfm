<?php

namespace MA\Service;

use Zend\EventManager\EventInterface;
use Base\Service\AbstractService;
use User\Entity\UserAwareInterface;

class UserService extends AbstractService
{
	/**
	 * @param EventInterface $e
	 */
	public function injectUser(EventInterface $e)
	{
		$source = $e->getTarget();

		if (!$source instanceof UserAwareInterface) {
			throw new \RuntimeException(sprintf("given class is not an UserAwareInterface",
				get_class($source)));
		}

		if (null === ($user = $this->getAuthService()->getIdentity())) {
			throw new \RuntimeException(sprintf("no user for given UserAwareInterface on %s",
				get_class($source)));
		}

		$source->setUser($user);
	}

	/**
	 * @var AuthenticationService
	 */
	protected $authService;

    /**
     * Get authService.
     *
     * @return AuthenticationService
     */
    public function getAuthService()
    {
		if ($this->authService === null) {
			$this->authService = $this->getServiceLocator()->get("zfcuser_auth_service");
		}

		return $this->authService;
    }

    /**
     * Set authService.
     *
     * @param AuthenticationService $authService
     * @return UserService 
     */
    public function setAuthService(AuthenticationService $authService)
    {
        $this->authService = $authService;
        return $this;
    }
}
