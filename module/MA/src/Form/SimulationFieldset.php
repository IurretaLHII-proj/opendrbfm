<?php

namespace MA\Form;

use Zend\Form\Fieldset;
use Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\InputFilter\InputFilterProviderInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

use MA\Entity\Simulation;

class SimulationFieldset extends Fieldset implements 
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

		/*
        $this->add([
             'type' => 'Text',
             'name' => 'who',
             'required' => true,
             'attributes' => [ 
                 'class' => 'form-control',
				 'rows' => 4,
             ],
             'options' => [
                 'label' => 'Who',
             ],
		], ['priority' => -9]);
		*/

        $this->add([
             'type' => 'ObjectSelect',
             'name' => 'who',
             'attributes' => [ 
                 'class' => 'form-control',
             ],
             'options' => [
				 'target_class' => 'MA\Entity\User',
             ],
        ]);

        $this->add([
             'type' => 'Text',
             'name' => 'when',
             'required' => true,
             'attributes' => [ 
                 'class' => 'form-control',
				 'rows' => 4,
             ],
             'options' => [
                 'label' => 'When',
             ],
        ], ['priority' => -9]);

        $this->add([
             'type' => 'Select',
             'name' => 'state',
             'required' => true,
             'attributes' => [ 
                 'class' => 'form-control',
             ],
             'options' => [
                 'label' => 'State',
				 'value_options' => [
				 	Simulation::STATE_CREATED		=> Simulation::STATE_CREATED,
				 	Simulation::STATE_NOT_NECESSARY => Simulation::STATE_NOT_NECESSARY,
				 	Simulation::STATE_IN_PROGRESS 	=> Simulation::STATE_IN_PROGRESS,
				 	Simulation::STATE_FINISHED 		=> Simulation::STATE_FINISHED,
				 	Simulation::STATE_CANCELED 		=> Simulation::STATE_CANCELED,
				 ],
             ],
        ], ['priority' => -9]);

		$this->add([
                'type' => 'Collection',
                'name' => 'suggestions',
				'options' => [
					'count' => 0,
					'allow_add' => true,
					'target_element' => [
						'type' => \MA\Form\NoteFieldset::class,
						'object' => \MA\Entity\Note\HintSuggestion::class,
					],
				],
            ],
            ['priority' => -10]
		);

		$this->add([
                'type' => 'Collection',
                'name' => 'effects',
				'options' => [
					'count' => 0,
					'allow_add' => true,
					'target_element' => [
						'type' => \MA\Form\NoteFieldset::class,
						'object' => \MA\Entity\Note\HintEffect::class,
					],
				],
            ],
            ['priority' => -10]
		);

		$this->add([
                'type' => 'Collection',
                'name' => 'preventions',
				'options' => [
					'count' => 0,
					'allow_add' => true,
					'target_element' => [
						'type' => \MA\Form\NoteFieldset::class,
						'object' => \MA\Entity\Note\HintPrevention::class,
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
			//'type' => \MA\InputFilter\SimulationInputFilter::class,
			'id' => [
				'required' => false,
			],
			'who' => [
				'required' => $this->get('state')->getValue() > Simulation::STATE_CREATED,
			],
			'when' => [
				'required' => $this->get('state')->getValue() > Simulation::STATE_CREATED,
				'validators' => [
					[
						'name' => \Zend\Validator\Date::class,
						'options' => ['format' => 'Y-m-d'],
					],
				],
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
