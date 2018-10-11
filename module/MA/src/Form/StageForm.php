<?php

namespace MA\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class StageForm extends Form implements InputFilterProviderInterface
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->add([
             'type' => 'TextArea',
             'name' => 'body',
             'required' => true,
             'attributes' => [ 
                 'class' => 'form-control',
				 'rows' => 4,
             ],
             'options' => [
                 'label' => 'Body',
             ],
        ], ['priority' => -9]);

        $this->add([
             'type' => 'ObjectSelect',
             'name' => 'parent',
             'attributes' => [ 
                 'class' => 'form-control',
             ],
             'options' => [
                 'label' => 'Parent',
				 'empty_option' => 'Choose parent team',
				 'target_class' => 'MA\Entity\Stage',
             ],
        ]);

		$this->add([
                'type' => 'Collection',
                'name' => 'images',
                'attributes' => [ 
                    'class' => 'images'
				],
				'options' => [
					'label' => 'Images',
					'count' => 0,
					'allow_add' => true,
					'target_element' => [
						'type' => \Image\Form\ImageFieldset::class,
						'object' => \MA\Entity\Image\IStage::class,
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
			'body' => [
				//'required' => false,
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
			'parent' => [
				'required' => false,
			]
		];
	}
}
