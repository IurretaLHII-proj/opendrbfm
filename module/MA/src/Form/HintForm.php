<?php

namespace MA\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class HintForm extends Form implements InputFilterProviderInterface
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->add([
             'type' => 'Number',
             'name' => 'priority',
             'required' => true,
             'attributes' => [ 
                 'class' => 'form-control',
				 'min' => -99,
				 'max' => 99,
             ],
             'options' => [
                 'label' => 'Priority',
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
				 'empty_option' => 'Choose hint type',
				 'target_class' => 'MA\Entity\HintType',
             ],
        ]);

        $this->add([
             'type' => 'Text',
             'name' => 'text',
             'required' => true,
             'attributes' => [ 
                 'class' => 'form-control',
				 'rows' => 4,
             ],
             'options' => [
                 'label' => 'Text',
             ],
        ], ['priority' => -9]);

        $this->add([
             'type' => 'Textarea',
             'name' => 'description',
             'required' => true,
             'attributes' => [ 
                 'class' => 'form-control',
				 'rows' => 4,
             ],
             'options' => [
                 'label' => 'Description',
             ],
        ], ['priority' => -9]);

		$this->add([
                'type' => 'Collection',
                'name' => 'parents',
                'attributes' => [ 
                    'class' => 'parents'
				],
				'options' => [
					'label' => 'Parents',
					'count' => 0,
					//'allow_add' => true,
					'target_element' => [
						'type' => \MA\Form\HintSelectorFieldset::class,
						'object' => \MA\Entity\Hint::class,
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
						'object' => \MA\Entity\Note\HintReason::class,
					],
				],
            ],
            ['priority' => -10]
		);

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
						'object' => \MA\Entity\Note\HintInfluence::class,
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
			'priority' => [
				'required' => true,
			],
			'text' => [
				'required' => false,
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
		];
	}
}
