<?php

namespace Image\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\InputFilter\InputFilterProviderInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class ImageUploadForm extends Form implements 
    ServiceLocatorAwareInterface, InputFilterProviderInterface
{
    const UPLOAD_TARGET = './public/img';

    /**
     * @var ServiceLocatorInterface
     */
    protected $sm;

    /**
     * @inheritDoc
     */
    public function setServiceLocator(ServiceLocatorInterface $sm)
    {
        $this->sm = $sm->getServiceLocator();
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getServiceLocator()
    {
        return $this->sm;
    }

    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->add([
             'type' => 'File',
             'name' => 'file',
             'attributes' => [ 
                 'class' => 'form-control',
             ],
             'options' => [
                 'label' => 'File',
             ],
        ]);


        parent::init();
    }

    /**
     * @inheritDoc
     */
    public function getInputFilterSpecification()
    {
        return [
            'file' => [
                'filters' => [
                    [
                        'name' => 'Zend\Filter\File\RenameUpload',
                        'options' => array(
                            'target' => static::UPLOAD_TARGET,
                            //'use_upload_name' => true, 
                            //'use_upload_extension' => true,
                            'overwrite' => true,
                        )
                    ]
                ],
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
            ],
        ];
    }
}

