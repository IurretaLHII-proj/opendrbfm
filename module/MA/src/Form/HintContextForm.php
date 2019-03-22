<?php

namespace MA\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class HintContextForm extends Form implements InputFilterProviderInterface
{
    /**
     * @inheritDoc
     */
    public function init()
    {
		$this->add([
                'type' => 'Collection',
                'name' => 'relateds',
				'options' => [
					'count' => 1,
					'target_element' => [
						'type' => \MA\Form\HintRelationFieldset::class,
						'object' => \MA\Entity\HintRelation::class,
					],
				],
            ],
            ['priority' => -10]
		);

		$this->add([
                'type' => 'Collection',
                'name' => 'relations',
				'options' => [
					'count' => 1,
					'target_element' => [
						'type' => \MA\Form\HintRelationFieldset::class,
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
					'count' => 1,
					'target_element' => [
						'type' => \MA\Form\SimulationFieldset::class,
						'object' => \MA\Entity\Simulation::class,
					],
				],
            ],
            ['priority' => -10]
		);

		$this->add([
                'type' => 'Collection',
                'name' => 'parents',
				'options' => [
					'count' => 0,
					'target_element' => [
						'type' => \MA\Form\HintContextFieldset::class,
						'object' => \MA\Entity\HintContext::class,
					],
				],
            ],
            ['priority' => -10]
		);

		$this->add([
                'type' => 'Collection',
                'name' => 'children',
				'options' => [
					'count' => 0,
					'target_element' => [
						'type' => \MA\Form\HintContextFieldset::class,
						'object' => \MA\Entity\HintContext::class,
					],
				],
            ],
            ['priority' => -10]
		);

		$this->add([
                'type' => 'Collection',
                'name' => 'reasons',
                'attributes' => [ 
                    'class' => 'reasons'
				],
				'options' => [
					'label' => 'Reasons',
					'count' => 0,
					'allow_add' => true,
					'target_element' => [
						'type' => \MA\Form\NoteFieldset::class,
						'object' => \MA\Entity\Note\ContextReason::class,
					],
				],
            ],
            ['priority' => -10]
		);

		$this->add([
                'type' => 'Collection',
                'name' => 'influences',
                'attributes' => [ 
                    'class' => 'influences'
				],
				'options' => [
					'label' => 'Influences',
					'count' => 0,
					'allow_add' => true,
					'target_element' => [
						'type' => \MA\Form\NoteFieldset::class,
						'object' => \MA\Entity\Note\ContextInfluence::class,
					],
				],
            ],
            ['priority' => -10]
		);

        $this->add([
                'type' => 'Submit',
                'name' => 'submit',
                'attributes' => [ 
                    'value' => 'Save',
                    'class' => 'btn btn-primary'
                ],
            ],
            ['priority' => -20]
        );

        parent::init();
    }

	/**
	 * @inheritDoc
	 */
	public function getInputFilterSpecification()
	{
		return [
		];
	}
}
