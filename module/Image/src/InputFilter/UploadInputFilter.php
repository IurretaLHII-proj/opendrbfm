<?php

namespace Image\InputFilter;

use Zend\InputFilter\InputFilter as BaseInputFilter;

class UploadInputFilter extends BaseInputFilter
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->add(
            array(
                'name' => 'file',
                'type' => 'Zend\InputFilter\FileInput',
                'filters' => array(
                    array(
                        'name' => 'Zend\Filter\File\RenameUpload',
                        'options' => array(
                            'target' => './public/img'
                        )
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'Zend\Validator\File\Extension',
                        'options' => array(
                            'extension' => array('png', 'jpg', 'jpeg')
                        )
                    ),
                    array(
                        'name' => 'Zend\Validator\File\Size',
                        'options' => array(
                            //'min' => '10kb',
                            'max' => '2MB'
                        )
                    )
                )
            ),
            'file'
        );

        parent::init();
    }
}
