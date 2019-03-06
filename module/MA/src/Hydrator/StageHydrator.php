<?php

namespace MA\Hydrator;

class StageHydrator extends \Zend\Hydrator\ClassMethods 
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
			'id' 	  	   => $object->getId(),
			//'name'   	   => 'Stage ' . $object->getLevel(),
			//'level'          => $object->getLevel(),
			'order'		   => $object->getOrder(),
			'body' 	  	   => $object->getBody(),
			'owner'   	   => $object->getUser(),
			'commentCount' => $object->getCommentCount(),
			'created' 	   => $object->getCreated(),
			'updated' 	   => $object->getUpdated(),
			'images'  	   => new \ZF\Hal\Collection($object->getImages()),
			'operations'   => new \ZF\Hal\Collection($object->getOperations()),
			//'hints'   	  => new \ZF\Hal\Collection($object->getHints()),
		];
    }
}
