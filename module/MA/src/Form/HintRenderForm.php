<?php

namespace MA\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

use MA\Entity\Simulation;

class HintRenderForm extends Form implements InputFilterProviderInterface
{
    /**
     * @inheritDoc
     */
    public function init()
    {
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
				 'rows' => 4,
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
			'who' => [
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
			//'when' => [
			//	'required' => false,
			//	'validators' => [
			//		[
			//			'name' => \Zend\Validator\Callback::class,
			//		    'options' => [
			//				'callback' => function($value, $context = []) {
			//					switch ($context['state']) {
			//						case \MA\Entity\Hint::STATE_NOT_NECESSARY:
			//							return true;	
			//						default:
			//							return false;
			//					}
			//				},
			//			],	
			//		],
			//	],
			//],
			'when' => [
				'required' => true,
				'validators' => [
					[
						'name' => \Zend\Validator\Date::class,
						'options' => ['format' => 'Y-m-d'],
					],
				],
			],
			'effect' => [
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
			'prevention' => [
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
			'state' => [
				'required' => true,
			],
		];
	}
}
