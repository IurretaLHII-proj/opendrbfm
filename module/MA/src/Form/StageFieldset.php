<?php

namespace MA\Form;

use Zend\Form\Fieldset;
use Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\InputFilter\InputFilterProviderInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class StageFieldset extends Fieldset implements 
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
             'required' => true,
             'attributes' => [ 
                 'class' => 'form-control',
             ],
             'options' => [
                 'label' => 'Id',
             ],
        ], ['priority' => -1]);

        $this->add([
             'type' => 'TextArea',
             'name' => 'body',
             'required' => true,
             'attributes' => [ 
                 'class' => 'form-control',
				 'rows' => 4,
             ],
             'options' => [
                 'label' => 'Body',
             ],
        ], ['priority' => -9]);

        $this->add([
             'type' => 'ObjectSelect',
             'name' => 'parent',
             'attributes' => [ 
                 'class' => 'form-control',
             ],
             'options' => [
                 'label' => 'Parent',
				 'empty_option' => 'Choose parent stage',
				 'target_class' => 'MA\Entity\Stage',
             ],
        ], ['priority' => -11]);

		$this->add([
             'type' => 'Collection',
             'name' => 'children',
             'attributes' => [ 
                 'class' => 'form-control',
             ],
             'options' => [
                 'label' => 'Children',
				 'count' => 0,
				 'target_element' => [
				 	'type' => StageSelectorFieldset::class,
				 	'object' => \MA\Entity\Stage::class,
				 ],
             ],
		], ['priority' => -10]);

        $this->add([
             'type' => 'ObjectSelect',
             'name' => 'material',
             'attributes' => [ 
                 'class' => 'form-control',
             ],
             'options' => [
                 'label' => 'Material',
				 'empty_option' => 'Choose material',
				 'target_class' => 'MA\Entity\Material',
             ],
        ]);

		$this->add([
                'type' => 'Collection',
                'name' => 'operations',
                'attributes' => [ 
                    'class' => 'operations'
				],
				'options' => [
					'label' => 'Operations',
					'count' => 1,
					'allow_add' => true,
					//'allow_remove' => false,
					'target_element' => [
						'type' => OperationSelectorFieldset::class,
						'object' => \MA\Entity\Operation::class,
					],
				],
            ],
            ['priority' => -10]
		);

		$this->add([
                'type' => 'Collection',
                'name' => 'images',
                'attributes' => [ 
                    'class' => 'images'
				],
				'options' => [
					'label' => 'Images',
					'count' => 0,
					'allow_add' => true,
					'target_element' => [
						'type' => \Image\Form\ImageFieldset::class,
						'object' => \MA\Entity\Image\IStage::class,
					],
				],
            ],
            ['priority' => -10]
		);

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
			'id' => [
				'required' => false,
			],
			'body' => [
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
			'parent' => [
				'required' => false,
			]
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
