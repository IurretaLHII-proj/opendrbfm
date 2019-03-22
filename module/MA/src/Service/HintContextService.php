<?php

namespace MA\Service;

use Zend\EventManager\EventInterface;
use Base\Service\AbstractService;

class HintContextService extends AbstractService
{
	/**
	 * @param EventInterface $e
	 */
	public function createContext(EventInterface $e)
	{
		$source = $e->getTarget();

		foreach ($source->getRelations() as $relation) {
			if (!$relation->getUser()) $this->getNavService()->triggerService(self::EVENT_CREATE, $relation);
		}
		foreach ($source->getRelateds() as $relation) {
			if (!$relation->getUser()) $this->getNavService()->triggerService(self::EVENT_CREATE, $relation);
		}

		foreach ($source->getParents() as $context) {
			if (!$context->getUser()) $this->getNavService()->triggerService(self::EVENT_CREATE, $context);
		}
		foreach ($source->getChildren() as $context) {
			if (!$context->getUser()) $this->getNavService()->triggerService(self::EVENT_CREATE, $context);
		}

		foreach ($source->getReasons() as $note) {
			if (!$note->getUser()) $this->getNavService()->triggerService(self::EVENT_CREATE, $note);
		}
		foreach ($source->getInfluences() as $note) {
			if (!$note->getUser()) $this->getNavService()->triggerService(self::EVENT_CREATE, $note);
		}

		foreach ($source->getSimulations() as $simulation) {
			if (!$simulation->getUser()) $this->getNavService()->triggerService(self::EVENT_CREATE, $simulation);
		}
	}
}
