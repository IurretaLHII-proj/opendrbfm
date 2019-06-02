<?php

namespace MA\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class ProcessForm extends Form implements InputFilterProviderInterface
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->add([
             'type' => 'Text',
             'name' => 'number',
             'attributes' => [ 
                 'class' => 'form-control',
				 'placeholder' => 'Project nº',
             ],
             'options' => [
                 'label' => 'Project Nº',
             ],
        ], ['priority' => -1]);

        $this->add([
             'type' => 'Text',
             'name' => 'code',
             'attributes' => [ 
                 'class' => 'form-control',
				 'placeholder' => 'Article code',
             ],
             'options' => [
                 'label' => 'Article code',
             ],
        ], ['priority' => -1]);

        $this->add([
             'type' => 'Number',
             'name' => 'line',
             'attributes' => [ 
                 'class' => 'form-control',
				 'placeholder' => 'Line',
				 'min' => 1,
				 'max' => 99,
             ],
             'options' => [
                 'label' => 'Line',
             ],
        ], ['priority' => -1]);

        $this->add([
             'type' => 'Text',
             'name' => 'title',
             'attributes' => [ 
                 'class' => 'form-control',
				 'placeholder' => 'Name',
             ],
             'options' => [
                 'label' => 'Name',
             ],
        ], ['priority' => 1]);

        /*$this->add([
             'type' => 'Text',
             'name' => 'machine',
             'attributes' => [ 
                 'class' => 'form-control',
				 'placeholder' => 'Machine',
             ],
             'options' => [
                 'label' => 'Machine',
             ],
        ], ['priority' => -7]);

        $this->add([
             'type' => 'Text',
             'name' => 'plant',
			 'required' => true,
             'attributes' => [ 
                 'class' => 'form-control',
				 'placeholder' => 'Productive plant',
             ],
             'options' => [
                 'label' => 'Plant',
             ],
		 ], ['priority' => -8]);*/

        $this->add([
             'type' => 'ObjectSelect',
             'name' => 'machine',
             'attributes' => [ 
             ],
             'options' => [
				 'target_class' => 'MA\Entity\Machine',
             ],
        ]);

        $this->add([
             'type' => 'ObjectSelect',
             'name' => 'plant',
             'attributes' => [ 
             ],
             'options' => [
				 'target_class' => 'MA\Entity\ProductivePlant',
             ],
        ]);

        $this->add([
             'type' => 'Text',
             'name' => 'pieceNumber',
			 'required' => true,
             'attributes' => [ 
                 'class' => 'form-control',
				 'placeholder' => 'Piece nª',
             ],
             'options' => [
                 'label' => 'Piece number',
             ],
        ], ['priority' => -8]);

        $this->add([
             'type' => 'Text',
             'name' => 'pieceName',
			 'required' => true,
             'attributes' => [ 
                 'class' => 'form-control',
				 'placeholder' => 'Piece name',
             ],
             'options' => [
                 'label' => 'Piece name',
             ],
        ], ['priority' => -8]);

        $this->add([
             'type' => 'Select',
             'name' => 'complexity',
             'attributes' => [ 
                 'class' => 'form-control',
				 'placeholder' => 'Complexity',
             ],
             'options' => [
                 'label' => 'Complexity',
				 'empty_option' => '-- Select Complexity --',
				 'value_options' => [
				 	\MA\Entity\Process::COMPLEXITY_SOFT => \MA\Entity\Process::COMPLEXITY_SOFT,
				 	\MA\Entity\Process::COMPLEXITY_MEDIUM => \MA\Entity\Process::COMPLEXITY_MEDIUM,
				 	\MA\Entity\Process::COMPLEXITY_HARD => \MA\Entity\Process::COMPLEXITY_HARD,
				 ],
             ],
        ], ['priority' => -8]);

        $this->add([
             'type' => 'ObjectSelect',
             'name' => 'customer',
             'attributes' => [ 
                 'class' => 'form-control',
             ],
             'options' => [
                 'label' => 'Customer',
				 'empty_option' => '-- Select Customer --',
				 'target_class' => 'MA\Entity\Customer',
             ],
        ]);

        $this->add([
             'type' => 'TextArea',
             'name' => 'body',
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
			'number' => [
				'required' => true,
				'filters' => [
					[
						'name' => \Zend\Filter\StringTrim::class,
					],
				],
				'validators' => [
					//[
					//	'name' => \Zend\Validator\Regex::class,
					//	'options' => ['pattern' => '(^-?\d*(\.\d+)?$)']
					//],
					[
						'name' => \Zend\Validator\StringLength::class,
						'options' => ['min' => 1, 'max' => 11],
					],
				],
			],
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
			'title' => [
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
			/*'plant' => [
				'required' => true,
				'filters' => [
					[
						'name' => \Zend\Filter\StringTrim::class,
					],
				],
				'validators' => [
					[
						'name' => \Zend\Validator\StringLength::class,
						'options' => ['min' => 3, 'max' => 64],
					],
				],
			],
			'machine' => [
				'required' => true,
				'filters' => [
					[
						'name' => \Zend\Filter\StringTrim::class,
					],
				],
				'validators' => [
					[
						'name' => \Zend\Validator\StringLength::class,
						'options' => ['min' => 3, 'max' => 64],
					],
				],
			],*/
			'pieceNumber' => [
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
						'options' => ['min' => 3, 'max' => 11],
					],
				],
			],
			'pieceName' => [
				'required' => true,
				'filters' => [
					[
						'name' => \Zend\Filter\StringTrim::class,
					],
				],
				'validators' => [
					[
						'name' => \Zend\Validator\StringLength::class,
						'options' => ['min' => 3, 'max' => 64],
					],
				],
			],
			'body' => [
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
