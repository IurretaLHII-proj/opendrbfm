<?php

namespace Image\InputFilter;

use Zend\InputFilter\InputFilter as BaseInputFilter;

class ImageInputFilter extends BaseInputFilter
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->add(
            array(
                'name' => 'id',
            ),
            'id'
        );

        $this->add(
            array(
                'name' => 'name',
            ),
            'name'
        );

        $this->add(
            array(
                'name' => 'type',
            ),
            'type'
        );

        $this->add(
            array(
                'name' => 'size',
            ),
            'size'
        );

        $this->add(
            array(
                'name'     => 'description',
                'required' => false
            ),
            'description'
        );

        parent::init();
    }
}
