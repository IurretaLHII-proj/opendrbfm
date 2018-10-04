<?php

namespace MA\Hydrator;

class ProcessHydrator extends \Zend\Hydrator\ClassMethods 
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
			'title'   => $object->getTitle(),
			'body' 	  => $object->getBody(),
			'created' => $object->getCreated()->getTimestamp(),
			'created' => $object->getUpdated()->getTimestamp(),
			'stages'  => new \ZF\Hal\Collection($object->getStages()),
		];
    }
}
