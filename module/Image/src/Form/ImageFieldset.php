<?php

namespace Image\Form;

use Zend\Form\Fieldset;
use Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\InputFilter\InputFilterProviderInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class ImageFieldset extends Fieldset implements 
    ServiceLocatorAwareInterface, InputFilterProviderInterface
{
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
        //$this->add([
        //     'type' => 'File',
        //     'name' => 'file',
        //     'attributes' => [ 
        //         'class' => 'form-control',
        //     ],
        //     'options' => [
        //         'label' => 'File',
        //     ],
        //]);

        $this->add([
             'type' => 'Textarea',
             'name' => 'description',
             'attributes' => [ 
                 'class' => 'form-control',
             ],
             'options' => [
                 'label' => 'Description',
             ],
        ]);

        $this->add([
             'type' => 'Hidden',
             'name' => 'id',
             'attributes' => [ 
                 'class' => 'form-control',
             ],
        ]);
        /*
        $this->add([
             'type' => 'Hidden',
             'name' => 'name',
             'attributes' => [ 
                 'class' => 'form-control',
             ],
        ]);

        $this->add([
             'type' => 'Hidden',
             'name' => 'type',
             'attributes' => [ 
                 'class' => 'form-control',
             ],
        ]);

        $this->add([
             'type' => 'Hidden',
             'name' => 'size',
             'attributes' => [ 
                 'class' => 'form-control',
             ],
         ]);
         */

        $em = $this->getServiceLocator()
                   ->get('Doctrine\ORM\EntityManager');
    
        $this->setHydrator(new DoctrineObject($em));
        //$this->setHydrator(new \GImage\Doctrine\Hydrator\ImageHydrator($em));

        parent::init();
    }

    /**
     * @inheritDoc
     */
    public function getInputFilterSpecification()
    {
		return [
			'id' => [
				'name' => 'id',
				'required' => true,
			],
			'description' => [
				'name' => 'id',
				'required' => false,
			],
		];
    }
}

