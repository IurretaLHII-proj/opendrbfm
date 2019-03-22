<?php

namespace MA\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class HintRelationForm extends Form
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->add([
                'type' => \MA\Form\HintRelationFieldset::class,
                'name' => 'relation',
                'options' => [ 
					'use_as_base_fieldset' => true,
                ],
            ],
            ['priority' => -20]
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
}
