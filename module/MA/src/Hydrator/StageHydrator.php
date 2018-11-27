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
			'id' 	  	  => $object->getId(),
			'level'   	  => $object->getLevel(),
			'version'     => $object->getVersion(),
			'body' 	  	  => $object->getBody(),
			'owner'   	  => $object->getUser(),
			'created' 	  => $object->getCreated()->getTimestamp(),
			'updated' 	  => $object->getUpdated()->getTimestamp(),
			'images'  	  => new \ZF\Hal\Collection($object->getImages()),
			'operations'  => new \ZF\Hal\Collection($object->getOperations()),
			'hints'   	  => new \ZF\Hal\Collection($object->getHints()),
		];
    }
}
