<?php

namespace User\Entity;

use ZfcUser\Entity\User as BaseUser;
use Zend\Permissions\Acl\Role\RoleInterface,
	Zend\Permissions\Acl\Resource\ResourceInterface;

abstract class User extends BaseUser implements
	UserInterface
	//ResourceInterface,
	//RoleInterface 
{
	/**
	 * @constants string
	 */
	const ROLE_GUEST = "guest";
	const ROLE_USER  = "user";
	const ROLE_ADMIN = "admin";

	/**
	 * @alias getUsername
	 */
	public function getName()
	{
		return $this->getUsername();
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->getName();
	}
}
