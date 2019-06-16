<?php

namespace MA\Service\Action;

use Zend\EventManager\EventInterface;
use MA\Service\AbstractActionService;
use DateTime;

class SimulationService extends AbstractActionService
{
	/**
	 * @var string
	 */
	protected $__className__ = \MA\Entity\Action\Simulation::class;

	/**
	 * @var array
	 */
	protected $changeSetFields = [
		'who',
	];

	/**
	 * @inheritDoc
	 */
	protected function getClassName() {
		return (string) $this->__className__;
	}

	/**
	 * @inheritDoc
	 */
	protected function relationsChangeSet($source, array $changeSet)
	{
		if (isset($changeSet['when'])) {
			$pre  = $changeSet['when'][0];
			$post = $changeSet['when'][1];
			if ($pre instanceof DateTime && $post instanceof DateTime) {
				if (!$pre->diff($post)->format("%a")) {
					unset($changeSet['when']);
				}
			}
		}

		return parent::relationsChangeSet($source, $changeSet);
	}
}
