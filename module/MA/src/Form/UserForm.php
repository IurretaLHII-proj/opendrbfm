<?php

namespace MA\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class UserForm extends Form
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->add([
			'type' => 'Select',
			'name' => 'roles',
            'attributes' => [ 
                'class' => 'form-control',
				'multiple' => true,
			],
			'options' => [
				'label' => 'Roles',
				'value_options' => [
					\MA\Entity\User::ROLE_USER => \MA\Entity\User::ROLE_USER,
					\MA\Entity\User::ROLE_ADMIN => \MA\Entity\User::ROLE_ADMIN,
					//\MA\Entity\User::ROLE_SUPER => \MA\Entity\User::ROLE_SUPER,
				]
			]
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
