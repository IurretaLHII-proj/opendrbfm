<?php

namespace MA\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class CommentForm extends Form implements InputFilterProviderInterface
{
    /**
     * @inheritDoc
     */
    public function init()
    {
		//$this->setAttribute('data-ng-controller', 'TextareaCtrl');

        $this->add([
             //'type' => 'Hidden',
             'type' => 'TextArea',
             'name' => 'body',
             'required' => true,
             'attributes' => [ 
				 //'data-ng-model' => 'posttext',
				 //'data-ng-change' => 'textChanged()',
				 //'data-text-angular' => null,
				 //'data-ta-text-editor-class' => 'clearfix border-around',
				 //'data-ta-html-editor-class' => 'border-around',
                 'class' => 'form-control',
				 'rows' => 4,
             ],
             'options' => [
                 'label' => 'Body',
             ],
        ], ['priority' => 9]);

        $this->add([
                'type' => 'Submit',
                'name' => 'submit',
                'attributes' => [ 
                    'value' => 'Save',
                    'class' => 'btn btn-primary'
                ],
            ],
            ['priority' => -22]
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
				'required' => true,
				'filters' => [
					['name' => \Zend\Filter\StringTrim::class,],
				],
			],
		];
	}
}
