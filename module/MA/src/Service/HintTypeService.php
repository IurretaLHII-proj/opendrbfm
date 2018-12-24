<?php

namespace MA\Service;

use Zend\EventManager\EventInterface;
use Base\Service\AbstractService;

class HintTypeService extends AbstractService
{
	/**
	 * @param EventInterface $e
	 */
	public function increaseErrors(EventInterface $e)
	{
		$e->getTarget()->getType()->increaseErrorCount();
	}

	/**
	 * @param EventInterface $e
	 */
	public function decreaseErrors(EventInterface $e)
	{
		$e->getTarget()->getType()->decreaseErrorCount();
	}
}
