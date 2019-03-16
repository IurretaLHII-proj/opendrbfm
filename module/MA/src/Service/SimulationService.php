<?php

namespace MA\Service;

use Zend\EventManager\EventInterface;
use Base\Service\AbstractService;

class SimulationService extends AbstractService
{
	/**
	 * @param EventInterface $e
	 */
	public function createSimulation(EventInterface $e)
	{
		$source = $e->getTarget();

		foreach ($source->getSuggestions() as $note) {
			$this->getNavService()->triggerService(self::EVENT_CREATE, $note);
		}
		foreach ($source->getEffects() as $note) {
			$this->getNavService()->triggerService(self::EVENT_CREATE, $note);
		}
		foreach ($source->getPreventions() as $note) {
			$this->getNavService()->triggerService(self::EVENT_CREATE, $note);
		}
	}
}
