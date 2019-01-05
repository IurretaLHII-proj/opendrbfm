<?php

namespace MA\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class StageCollectionForm extends Form implements InputFilterProviderInterface
{
    /**
     * @inheritDoc
     */
    public function init()
    {
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
			'stages' => [
				'required' => true,
			],
		];
	}
}
