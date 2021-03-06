<?php

namespace MA\Service\Action;

use Zend\EventManager\EventInterface;
use MA\Service\AbstractActionService;

class HintInfluenceService extends AbstractActionService
{
	/**
	 * @var string
	 */
	protected $__className__ = \MA\Entity\Action\HintInfluence::class;

	/**
	 * @inheritDoc
	 */
	protected function getClassName() {
		return (string) $this->__className__;
	}
}
