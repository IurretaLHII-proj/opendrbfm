<?php

namespace MA\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use MA\Entity\Version;

class OperationReplaceForm extends Form 
{
    /**
     * @inheritDoc
     */
    public function init()
    {
		$this->add([
             'type' => 'ObjectSelect',
             'name' => 'operation',
             'options' => [
				 'target_class' => 'MA\Entity\Operation',
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
}
