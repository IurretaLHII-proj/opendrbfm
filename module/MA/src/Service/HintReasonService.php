<?php

namespace MA\Service;

use Zend\EventManager\EventInterface;
use Base\Service\AbstractService;
use MA\Entity\HintInterface;

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

	/**
     * @param EventInterface $e
     */
    public function cloneReasons(EventInterface $e)
    {
        $hint   = $e->getTarget();
        $params = $e->getParams();

        if (!(array_key_exists('origin', $params) && $params['origin'] instanceof HintInterface)) {
            throw new \InvalidArgumentException(sprintf("Clone origin parameter missing"));
        }

        foreach ($params['origin']->getReasons() as $reason) {
			$_reason = clone $reason;
			foreach ($reason->getNotes() as $note) {
				$_note = clone $note;
				$_reason->addNote($_note);
                $this->triggerService($e->getName(), $_note, ['origin' => $note]);
			}
			$hint->addReason($_reason);
            $this->triggerService($e->getName(), $_reason, ['origin' => $reason]);
        }
    }
}
