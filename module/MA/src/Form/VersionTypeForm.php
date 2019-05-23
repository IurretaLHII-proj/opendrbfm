<?php

namespace MA\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class VersionTypeForm extends Form implements InputFilterProviderInterface
{
    /**
     * @inheritDoc
     */
    public function init()
    {
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
             'type' => 'TextArea',
             'name' => 'description',
             'attributes' => [ 
                 'class' => 'form-control',
				 'placeholder' => 'Description..',
				 'rows' => 4,
             ],
             'options' => [
                 'label' => 'Body',
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
