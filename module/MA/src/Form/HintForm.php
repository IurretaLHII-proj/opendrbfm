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
             'type' => 'ObjectSelect',
             'name' => 'parents',
             'attributes' => [ 
                 'class' => 'form-control',
				 'multiple' => true,
             ],
             'options' => [
                 'label' => 'Hint parents',
				 'empty_option' => '-- Select --',
				 'target_class' => 'MA\Entity\Hint',
             ],
        ]);

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
			'text' => [
				'required' => true,
				'filters' => [
					[
						'name' => \Zend\Filter\StringTrim::class,
					],
				],
				'validators' => [
					[
						'name' => \Zend\Validator\StringLength::class,
						'options' => ['min' => 10, 'max' => 255],
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
						'options' => ['min' => 10, 'max' => 800],
					],
				],
			],
			'parents' => [
				'required' => false,
			],
		];
	}
}
