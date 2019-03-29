<?php

namespace MA\Service;

use Zend\EventManager\EventInterface;
use Base\Service\AbstractService;

class HintReasonService extends AbstractService
{
	/**
	 * @param EventInterface $e
	 */
	public function createDependencies(EventInterface $e)
	{
		$source = $e->getTarget();

		foreach ($source->getNotes() as $note) {
			$this->getNavService()->triggerService(self::EVENT_CREATE, $note);
		}

		foreach ($source->getRelations() as $relation) {
			$this->getNavService()->triggerService(self::EVENT_CREATE, $relation);
		}

		foreach ($source->getInfluences() as $influence) {
			$this->getNavService()->triggerService(self::EVENT_CREATE, $influence);
		}
	}
}
