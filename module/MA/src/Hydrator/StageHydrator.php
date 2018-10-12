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
			'id' 	  => $object->getId(),
			'body' 	  => $object->getBody(),
			'owner'   => $object->getUser(),
			'created' => $object->getCreated()->getTimestamp(),
			'created' => $object->getUpdated()->getTimestamp(),
			'images'  => new \ZF\Hal\Collection($object->getImages()),
			'hints'   => new \ZF\Hal\Collection($object->getHints()),
		];
    }
}
