<?php

namespace MA\Service;

use Zend\EventManager\EventInterface;
use Base\Service\AbstractService;
use MA\Entity\HintReasonInterface,
	MA\Entity\HintRelation;

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

	/**
     * @param EventInterface $e
     */
    public function cloneRelations(EventInterface $e)
    {
        $reason = $e->getTarget();
        $params = $e->getParams();

        if (!(array_key_exists('origin', $params) && $params['origin'] instanceof HintReasonInterface)) {
            throw new \InvalidArgumentException(sprintf("Clone origin parameter missing"));
        }

        foreach ($params['origin']->getRelations() as $rel) {
			
			$stage  = $reason->getVersion()->getStage($rel->getRelation()->getStage()->getOrder());
			
			foreach ($stage->getHints() as $hint) {
				if ($hint->getType() === $rel->getRelation()->getHint()->getType()) {
					// FIXME
					if (false !== ($r = $hint->getReasons()->first()) && false !== ($i = $r->getInfluences()->first())) {
						$relation = new HintRelation();
						$relation->setRelation($i);
						$reason->addRelation($relation);
						$this->triggerService($e->getName(), $relation, ['origin' => $rel]);
					}
				}
			}	
        }
    }
}
