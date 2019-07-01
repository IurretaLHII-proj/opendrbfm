<?php

namespace MA\Service;

use Zend\EventManager\EventInterface;
use Base\Service\AbstractService;
use MA\Entity\HintReasonInterface;

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

	/**
     * @param EventInterface $e
     */
    public function cloneInfluences(EventInterface $e)
    {
        $reason = $e->getTarget();
        $params = $e->getParams();

        if (!(array_key_exists('origin', $params) && $params['origin'] instanceof HintReasonInterface)) {
            throw new \InvalidArgumentException(sprintf("Clone origin parameter missing"));
		}
		
		foreach ($params['origin']->getInfluences() as $influence) {
			$_influence = clone $influence;
			foreach ($influence->getNotes() as $note) {
                $_note = clone $note;
				$_influence->addNote($_note);
				$this->triggerService($e->getName(), $_note, ['origin' => $note]);
			}
			$reason->addInfluence($_influence);
			$this->triggerService($e->getName(), $_influence, ['origin' => $influence]);
		}
    }
}
