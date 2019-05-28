<?php

namespace Base;

return [
	'zfctwig' => [
        'helper_manager' => [ 
			'invokables' => [
            	"headTitle" 		=> \Zend\View\Helper\HeadTitle::class,
            	"headScript" 		=> \Zend\View\Helper\HeadScript::class,
            	"partial" 			=> \Zend\View\Helper\Partial::class,
				'form'              => \Zend\Form\View\Helper\Form::class,
				'formElement'       => \Zend\Form\View\Helper\FormElement::class,
				'formElementErrors' => \Zend\Form\View\Helper\FormElementErrors::class,
				'Json'				=> \Zend\View\Helper\Json::class,
			],
			'factories' => [
				'hal'				=> Hal\Factory\HalViewHelperFactory::class,
			],
        ],
		'extensions' => [
		],
	 ],
];
