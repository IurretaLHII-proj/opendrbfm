<?php

namespace Image\Hydrator;

class ImageHydrator extends \Zend\Hydrator\ClassMethods 
{
    /**
     * @inheritDoc
     */
    public function hydrate(array $data, $object)
    {
        if (is_array($data) && isset($data['file']) && is_array($data['file'])) {
                $data = array_merge(
                        $data,
                        $data['file']
                );

                $data['name'] = $data['tmp_name'];
        }

        return parent::hydrate($data, $object);
    }

    /**
     * @inheritDoc
     */
	public function extract($object) 
	{
    	//not used
    }
}
