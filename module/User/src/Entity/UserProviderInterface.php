<?php

namespace User\Entity;

interface UserProviderInterface
{
	/**
	 * @return UserInterface
	 */
	public function getUser();
}
