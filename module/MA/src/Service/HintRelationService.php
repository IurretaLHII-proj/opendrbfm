<?php

namespace MA\Service;

use Zend\EventManager\EventInterface;
use Base\Service\AbstractService;

class HintRelationService extends AbstractService
{
	/**
	 * @param EventInterface $e
	 */
	public function createRelation(EventInterface $e)
	{
		$source = $e->getTarget();
		
		if (!$source->getSource()->getUser()) {
			$this->getNavService()->triggerService(self::EVENT_CREATE, $source->getSource());
		}
		
		if (!$source->getRelation()->getUser()) {
			$this->getNavService()->triggerService(self::EVENT_CREATE, $source->getRelation());
		}
	}
}
