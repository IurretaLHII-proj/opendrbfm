<?php

namespace MA\Service\Action;

use Zend\EventManager\EventInterface;
use MA\Service\AbstractActionService;

class VersionService extends AbstractActionService
{
	/**
	 * @var string
	 */
	protected $__className__ = \MA\Entity\Action\Version::class;

	/**
	 * @var array
	 */
	protected $changeSetFields = [
		'parent',
		'material',
		'type',
	];

	/**
	 * @inheritDoc
	 */
	protected function getClassName() {
		return (string) $this->__className__;
	}
}
