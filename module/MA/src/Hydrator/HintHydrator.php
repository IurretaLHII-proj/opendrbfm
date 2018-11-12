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
			'text' 	  	  => $object->getText(),
			'name' 	  	  => $object->getName(),
			'description' => $object->getDescription(),
			'level'		  => $object->getStage()->getLevel(),
			'owner'   	  => $object->getUser(),
			'created' 	  => $object->getCreated()->getTimestamp(),
			'updated' 	  => $object->getUpdated()->getTimestamp(),
			'parents' 	  => new \ZF\Hal\Collection($object->getParents()),
			'reasons' 	  => new \ZF\Hal\Collection($object->getReasons()),
			'suggestions' => new \ZF\Hal\Collection($object->getSuggestions()),
			'influences'  => new \ZF\Hal\Collection($object->getInfluences()),
		];
    }
}
