<?php

namespace MA\Form;

use Zend\Form\Form,
	Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\ServiceLocatorAwareInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class OperationFieldset extends Fieldset implements
	ServiceLocatorAwareInterface, InputFilterProviderInterface
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->add([
             'type' => 'Number',
             'name' => 'id',
             'required' => false,
             'attributes' => [ 
                 'class' => 'form-control',
             ],
             'options' => [
                 'label' => 'Id',
             ],
        ], ['priority' => -1]);

        $this->add([
             'type' => 'Text',
             'name' => 'name',
             'required' => true,
             'attributes' => [ 
                 'class' => 'form-control',
				 'placeholder' => 'Title',
             ],
             'options' => [
                 'label' => 'Title',
             ],
        ], ['priority' => -8]);

        $this->add([
             'type' => 'TextArea',
             'name' => 'description',
             'required' => true,
             'attributes' => [ 
                 'class' => 'form-control',
				 'placeholder' => 'Description..',
				 'rows' => 4,
             ],
             'options' => [
                 'label' => 'Description',
             ],
        ], ['priority' => -9]);

        $this->add([
             'type' => 'ObjectSelect',
             'name' => 'type',
             'attributes' => [ 
                 'class' => 'form-control',
             ],
             'options' => [
                 'label' => 'Type',
				 'empty_option' => 'Choose operation type',
				 'target_class' => 'MA\Entity\OperationType',
             ],
        ]);

        $em = $this->getServiceLocator()
                   ->get('Doctrine\ORM\EntityManager');
    
        $this->setHydrator(new DoctrineObject($em));

        parent::init();
    }

	/**
	 * @inheritDoc
	 */
	public function getInputFilterSpecification()
	{
		return [
			'name' => [
				'required' => true,
				'filters' => [
					[
						'name' => \Zend\Filter\StringTrim::class,
					],
				],
				'validators' => [
					[
						'name' => \Zend\Validator\StringLength::class,
						'options' => ['min' => 3, 'max' => 255],
					],
				],
			],
			'description' => [
				'required' => false,
				'filters' => [
					[
						'name' => \Zend\Filter\StringTrim::class,
					],
					[
						'name' => \Zend\Filter\ToNull::class,
					],
				],
				'validators' => [
					[
						'name' => \Zend\Validator\StringLength::class,
						'options' => ['min' => 3, 'max' => 800],
					],
				],
			],
			'id' => [
				'required' => false,
			],
			'type' => [
				'required' => false,
			],
		];
	}

    /**
	 *
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
}
