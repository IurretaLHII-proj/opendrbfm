<?php

namespace MA\Form;

use Zend\Form\Fieldset;
use Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\InputFilter\InputFilterProviderInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class HintInfluenceFieldset extends Fieldset implements 
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
        ], ['priority' => -1]);

		$this->add([
                'type' => 'Collection',
                'name' => 'notes',
				'options' => [
					'count' => 0,
					'allow_add' => true,
					'create_new_objects' => true,
					'should_create_template' => true,
					'target_element' => [
						'type' => \MA\Form\NoteFieldset::class,
						'object' => \MA\Entity\Note\HintInfluence::class,
					],
				],
            ],
            ['priority' => -10]
		);

		$this->add([
                'type' => 'Collection',
                'name' => 'relations',
				'options' => [
					'target_element' => [
						'type' => \MA\Form\HintRelationReasonFieldset::class,
						'object' => \MA\Entity\HintRelation::class,
					],
				],
            ],
            ['priority' => -10]
		);

		$this->add([
                'type' => 'Collection',
                'name' => 'simulations',
				'options' => [
					'target_element' => [
						'type' => \MA\Form\SimulationFieldset::class,
						'object' => \MA\Entity\Simulation::class,
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
