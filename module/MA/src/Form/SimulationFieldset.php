<?php

namespace MA\Form;

use Zend\Form\Fieldset;
use Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\InputFilter\InputFilterProviderInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

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

		$this->add([
                'type' => 'Collection',
                'name' => 'suggestions',
                'attributes' => [ 
                    'class' => 'suggestions'
				],
				'options' => [
					'label' => 'Suggestions',
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
