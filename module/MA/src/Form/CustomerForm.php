<?php

namespace MA\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class CustomerForm extends Form implements InputFilterProviderInterface
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->add([
             'type' => 'Text',
             'name' => 'code',
             'attributes' => [ 
                 'class' => 'form-control',
				 'placeholder' => 'Code',
             ],
             'options' => [
                 'label' => 'Code',
             ],
        ], ['priority' => -1]);

        $this->add([
             'type' => 'Text',
             'name' => 'name',
             'attributes' => [ 
                 'class' => 'form-control',
				 'placeholder' => 'Name',
             ],
             'options' => [
                 'label' => 'Name',
             ],
        ], ['priority' => 1]);

        $this->add([
             'type' => 'Text',
             'name' => 'email',
             'attributes' => [ 
                 'class' => 'form-control',
				 'placeholder' => 'Email',
             ],
             'options' => [
                 'label' => 'Email',
             ],
        ], ['priority' => 1]);

        $this->add([
             'type' => 'Text',
             'name' => 'phone',
             'attributes' => [ 
                 'class' => 'form-control',
				 'placeholder' => 'Phone',
             ],
             'options' => [
                 'label' => 'Phone',
             ],
        ], ['priority' => 1]);

        $this->add([
             'type' => 'Text',
             'name' => 'contact',
             'attributes' => [ 
                 'class' => 'form-control',
				 'placeholder' => 'Contact',
             ],
             'options' => [
                 'label' => 'Contact',
             ],
        ], ['priority' => 1]);

        $this->add([
             'type' => 'TextArea',
             'name' => 'description',
             'attributes' => [ 
                 'class' => 'form-control',
				 'placeholder' => 'Description..',
				 'rows' => 4,
             ],
             'options' => [
                 'label' => 'Description',
             ],
        ], ['priority' => -9]);

        $this->add([
                'type' => 'Submit',
                'name' => 'submit',
                'attributes' => [ 
                    'value' => 'Save',
                    'class' => 'btn btn-success'
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
			'code' => [
				'required' => true,
				'filters' => [
					[
						'name' => \Zend\Filter\StringTrim::class,
					],
				],
				'validators' => [
					[
						'name' => \Zend\Validator\Regex::class,
						'options' => ['pattern' => '(^-?\d*(\.\d+)?$)']
					],
					[
						'name' => \Zend\Validator\StringLength::class,
						'options' => ['min' => 1, 'max' => 11],
					],
				],
			],
			'name' => [
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
			'email' => [
				'required' => true,
				'validators' => [
					[
						'name' => 'EmailAddress',
					],
				],
			],
			'phone' => [
				'required' => true,
				'validators' => [
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
