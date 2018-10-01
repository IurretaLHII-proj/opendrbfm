<?php

namespace User\Authorization\Assertion;

use Zend\Permissions\Acl\Assertion\AssertionInterface;

abstract class AbstractAssertion implements AssertionInterface
{
	/**
	 * @param \User\Entity\UserInterface $loggedUser
	 */
	public function __construct(\User\Entity\UserInterface $loggedUser = null)
	{
		$this->loggedUser = $loggedUser;
	}
}
