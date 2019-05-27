<?php

namespace MA\Hydrator\Expanded;

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
			'name' 	  	   => $object->getName(),
			'description'  => $object->getDescription(),
			'owner'   	   => $object->getUser(),
			'commentCount' => $object->getCommentCount(),
			'created' 	   => $object->getCreated(),
			'updated' 	   => $object->getUpdated(),
			'operation'    => $object->getOperation(),
			'stage'    	   => $object->getStage(),
			'process'  	   => $object->getProcess(),
			'version'	   => $object->getVersion(),
			'reasons'      => new \ZF\Hal\Collection($object->getReasons()),
		];
    }
}
