<?php

namespace MA\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class HintReasonForm extends Form implements InputFilterProviderInterface
{
    /**
     * @inheritDoc
     */
    public function init()
	{
		$this->add([
                'type' => 'Collection',
                'name' => 'notes',
				'options' => [
					'count' => 0,
					'target_element' => [
						'type' => \MA\Form\NoteFieldset::class,
						'object' => \MA\Entity\Note\HintReason::class,
					],
				],
            ],
            ['priority' => -10]
		);

		$this->add([
                'type' => 'Collection',
                'name' => 'relations',
				'options' => [
					'count' => 0,
					'target_element' => [
						'type' => \MA\Form\HintRelationInfluenceFieldset::class,
						'object' => \MA\Entity\HintRelation::class,
					],
				],
            ],
            ['priority' => -10]
		);

		$this->add([
                'type' => 'Collection',
                'name' => 'influences',
				'options' => [
					'count' => 0,
					'target_element' => [
						'type' => \MA\Form\HintInfluenceFieldset::class,
						'object' => \MA\Entity\HintInfluence::class,
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
