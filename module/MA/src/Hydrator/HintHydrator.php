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
			'description' => $object->getDescription(),
			'owner'   	  => $object->getUser(),
			'created' 	  => $object->getCreated()->getTimestamp(),
			'updated' 	  => $object->getUpdated()->getTimestamp(),
			'parents' 	  => new \ZF\Hal\Collection($object->getParents()),
		];
    }
}
