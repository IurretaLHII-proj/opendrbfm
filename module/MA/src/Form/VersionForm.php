<?php

namespace MA\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use MA\Entity\Version;

class VersionForm extends Form implements InputFilterProviderInterface
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->add([
             'type' => 'Text',
             'name' => 'name',
             'required' => true,
             'attributes' => [ 
                 'class' => 'form-control',
				 'rows' => 4,
             ],
        ], ['priority' => -9]);

        $this->add([
             'type' => 'TextArea',
             'name' => 'description',
             'required' => true,
             'attributes' => [ 
                 'class' => 'form-control',
				 'rows' => 4,
             ],
        ], ['priority' => -9]);

        $this->add([
             'type' => 'Select',
             'name' => 'state',
             'required' => true,
             'options' => [
                 'label' => 'State',
				 'value_options' => [
				 	Version::STATE_IN_PROGRESS 	=> Version::STATE_IN_PROGRESS,
				 	Version::STATE_APROVED 		=> Version::STATE_APROVED,
				 	Version::STATE_CANCELED 	=> Version::STATE_CANCELED,
				 ],
             ],
        ], ['priority' => -9]);

        $this->add([
             'type' => 'ObjectSelect',
             'name' => 'material',
             'attributes' => [ 
                 'class' => 'form-control',
             ],
             'options' => [
                 'label' => 'Material',
				 'empty_option' => 'Choose material',
				 'target_class' => 'MA\Entity\Material',
             ],
        ]);

		$this->add([
                'type' => 'Collection',
                'name' => 'stages',
				'options' => [
					'label' => 'Stages',
					'target_element' => [
						'type' => \MA\Form\StageFieldset::class,
						'object' => \MA\Entity\Stage::class,
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
