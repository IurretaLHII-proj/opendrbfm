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
			'id' 	 	   => $object->getId(),
			'priority' 	   => $object->getPriority(),
			'color'		   => $object->getColor(),
			'type'		   => $object->getType(),
			//'text' 	  	   => $object->getType()->getTitle(),
			//'level'		   => $object->getStage()->getLevel(),
			'name' 	  	   => $object->getName(),
			'description'  => $object->getDescription(),
			'owner'   	   => $object->getUser(),
			'commentCount' => $object->getCommentCount(),
			'created' 	   => $object->getCreated(),
			'updated' 	   => $object->getUpdated(),
			'operation'    => $object->getOperation(),
			'reasons'      => new \ZF\Hal\Collection($object->getReasons()),
		];
    }
}
