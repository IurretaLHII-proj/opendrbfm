<?php

namespace User\Entity;

interface UserAwareInterface extends UserProviderInterface
{
	/**
	 * @param UserInterface $user
	 * @return UserAwareInterface
	 */
	public function setUser(UserInterface $user);
}
