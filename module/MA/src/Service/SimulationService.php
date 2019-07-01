<?php

namespace MA\Service;

use Zend\EventManager\EventInterface;
use Base\Service\AbstractService;
use MA\Entity\HintInfluenceInterface;

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

	/**
     * @param EventInterface $e
     */
    public function cloneSimulations(EventInterface $e)
    {
        $influence = $e->getTarget();
        $params    = $e->getParams();

        if (!(array_key_exists('origin', $params) && $params['origin'] instanceof HintInfluenceInterface)) {
            throw new \InvalidArgumentException(sprintf("Clone origin parameter missing"));
        }

        foreach ($params['origin']->getSimulations() as $simulation) {
            $_simulation = clone $simulation;
            foreach ($simulation->getSuggestions() as $note) {
                $_note = clone $note;
                $this->triggerService($e->getName(), $_note, ['origin' => $note]);
                $_simulation->addSuggestion($_note);
			}
			foreach ($simulation->getEffects() as $note) {
                $_note = clone $note;
                $this->triggerService($e->getName(), $_note, ['origin' => $note]);
                $_simulation->addEffect($_note);
			}
			foreach ($simulation->getPreventions() as $note) {
                $_note = clone $note;
                $this->triggerService($e->getName(), $_note, ['origin' => $note]);
                $_simulation->addPrevention($_note);
			}
            $this->triggerService($e->getName(), $_simulation, ['origin' => $simulation]);
            $influence->addSimulation($_simulation);
        }
    }
}
