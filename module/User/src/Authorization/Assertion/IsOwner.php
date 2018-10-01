<?php

namespace User\Authorization\Assertion;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

class IsOwner extends AbstractAssertion 
{
	/**
	 * @inheritDoc
	 */
    public function assert(Acl $acl, RoleInterface $role = null, ResourceInterface $resource = null, $privilege = null)
	{
		//var_dump($role);

		if ($resource instanceof \User\Entity\UserInterface) {
			$user = $resource;
		}
		elseif ($resource instanceof \User\Entity\UserProviderInterface) {
			$user = $resource->getUser();
		}
		else {
			throw new \InvalidArgumentException(sprintf("Given %s class must implements %s", 
				get_class($resource), \User\Entity\UserProviderInterface::class));
		}

		return $user === $this->loggedUser;
	}
}
