<?php

namespace MA\Service;

use Zend\EventManager\EventInterface;
use Base\Service\AbstractService;

class HintInfluenceService extends AbstractService
{
	/**
	 * @param EventInterface $e
	 */
	public function createDependencies(EventInterface $e)
	{
		$source = $e->getTarget();

		if (!$source->getReason()->getUser()) {
			$this->getNavService()->triggerService(self::EVENT_CREATE, $source->getReason());
		}

		foreach ($source->getNotes() as $note) {
			$this->getNavService()->triggerService(self::EVENT_CREATE, $note);
		}

		foreach ($source->getRelations() as $relation) {
			$this->getNavService()->triggerService(self::EVENT_CREATE, $relation);
		}

		foreach ($source->getSimulations() as $simulation) {
			$this->getNavService()->triggerService(self::EVENT_CREATE, $simulation);
		}
	}
}
