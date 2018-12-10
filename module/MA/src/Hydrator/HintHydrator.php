<?php

namespace MA\Hydrator;

class HintHydrator extends \Zend\Hydrator\ClassMethods 
{
    /**
     * @inheritDoc
     */
    public function hydrate(array $data, $object)
    {
    	//not used
    }

    /**
     * @inheritDoc
     */
	public function extract($object) 
	{
		return [
			'id' 	 	  => $object->getId(),
			'priority' 	  => $object->getPriority(),
			'type'		  => $object->getType(),
			'text' 	  	  => $object->getType()->getTitle(),
			'name' 	  	  => $object->getName(),
			'description' => $object->getDescription(),
			'level'		  => $object->getStage()->getLevel(),
			'owner'   	  => $object->getUser(),
			'state' 	  => $object->getState(),
			'who' 	  	  => $object->getWho(),
			'when' 	  	  => null !== ($w = $object->getWhen()) ? $w->getTimestamp() : $w,
			'created' 	  => $object->getCreated()->getTimestamp(),
			'updated' 	  => $object->getUpdated()->getTimestamp(),
			'operation'   => $object->getOperation(),
			'parents' 	  => new \ZF\Hal\Collection($object->getParents()),
			'reasons' 	  => new \ZF\Hal\Collection($object->getReasons()),
			'suggestions' => new \ZF\Hal\Collection($object->getSuggestions()),
			'influences'  => new \ZF\Hal\Collection($object->getInfluences()),
		];
    }
}
