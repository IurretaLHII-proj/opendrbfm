<?php

namespace MA\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\InputFilter\InputFilterProviderInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class HintRelationInfluenceForm extends Form 
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->add([
                'type' => HintRelationInfluenceFieldset::class,
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
