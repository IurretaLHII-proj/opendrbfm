<?php

namespace MA\Hydrator\Expanded;

class CommentHydrator extends \Zend\Hydrator\ClassMethods 
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
			'body' 	  	   => $object->getBody(),
			'owner'   	   => $object->getUser(),
			'class'		   => get_class($object),
			'commentCount' => $object->getCommentCount(),
			'created' 	   => $object->getCreated(),
			'parent'	   => $object->getParent(),
			'process'	   => $object->getProcess(),
			'source'	   => $object->getSource(),
			'suscribers'   => new \ZF\Hal\Collection($object->getSuscribers()),
		];
    }
}
