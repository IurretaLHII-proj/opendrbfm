<?php

namespace MA\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

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
				 	\MA\Entity\Hint::STATE_CREATED	=>  \MA\Entity\Hint::STATE_CREATED,
				 	\MA\Entity\Hint::STATE_NOT_NECESSARY => \MA\Entity\Hint::STATE_NOT_NECESSARY,
				 	\MA\Entity\Hint::STATE_IN_PROGRESS => \MA\Entity\Hint::STATE_IN_PROGRESS,
				 	\MA\Entity\Hint::STATE_FINISHED => \MA\Entity\Hint::STATE_FINISHED,
				 	\MA\Entity\Hint::STATE_CANCELED => \MA\Entity\Hint::STATE_CANCELED,
				 ],
             ],
        ], ['priority' => -9]);

        $this->add([
             'type' => 'Textarea',
             'name' => 'effect',
             'required' => true,
             'attributes' => [ 
                 'class' => 'form-control',
				 'rows' => 4,
             ],
             'options' => [
                 'label' => 'Effect',
             ],
        ], ['priority' => -9]);

        $this->add([
             'type' => 'Textarea',
             'name' => 'prevention',
             'required' => true,
             'attributes' => [ 
                 'class' => 'form-control',
				 'rows' => 4,
             ],
             'options' => [
                 'label' => 'Prevention',
             ],
        ], ['priority' => -9]);

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
