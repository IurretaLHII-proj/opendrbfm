<?php

namespace MA\Service\Action;

use Zend\EventManager\EventInterface;
use MA\Service\AbstractActionService;

class StageService extends AbstractActionService
{
	/**
	 * @var string
	 */
	protected $__className__ = \MA\Entity\Action\Stage::class;

	/**
	 * @var array
	 */
	protected $changeSetFields = [
		'images',
		'operations',
	];

	/**
	 * @inheritDoc
	 */
	protected function getClassName() {
		return (string) $this->__className__;
	}
}
