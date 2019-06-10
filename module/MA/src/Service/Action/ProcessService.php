<?php

namespace MA\Service\Action;

use Zend\EventManager\EventInterface;
use MA\Service\AbstractActionService;

class ProcessService extends AbstractActionService
{
	/**
	 * @var string
	 */
	protected $__className__ = \MA\Entity\Action\Process::class;

	/**
	 * @inheritDoc
	 */
	protected function getClassName() {
		return (string) $this->__className__;
	}

	/**
	 * @var array
	 */
	protected $changeSetFields = [
		'complexity',
		'machine',
		'plant',
	];
}
