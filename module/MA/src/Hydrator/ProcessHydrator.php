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
			'id' 	   		=> $object->getId(),
			'title'    		=> $object->getTitle(),
			'number'    	=> $object->getNumber(),
			'code'    	  	=> $object->getCode(),
			'line'    	  	=> $object->getLine(),
			'machine'     	=> $object->getMachine(),
			'plant'    	  	=> $object->getPlant(),
			'complexity'  	=> $object->getComplexity(),
			'pieceNumber' 	=> $object->getPieceNumber(),
			'pieceName'   	=> $object->getPieceName(),
			'title'    	  	=> $object->getTitle(),
			'body' 	   	  	=> $object->getBody(),
			'owner'    	  	=> $object->getUser(),
			'customer'    	=> $object->getCustomer(),
			'created'  	  	=> $object->getCreated()->getTimestamp(),
			'updated'  	  	=> $object->getUpdated()->getTimestamp(),
			'versions' 	  	=> new \ZF\Hal\Collection($object->getVersions()),
			//'stages'   	  	=> new \ZF\Hal\Collection($object->getStages()),
		];
    }
}
