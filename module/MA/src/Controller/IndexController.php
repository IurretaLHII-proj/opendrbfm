<?php

namespace MA\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator,
	Zend\Paginator\Adapter\ArrayAdapter;
use Zend\View\Model\ViewModel,
	Zend\View\Model\ModelInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class IndexController extends AbstractActionController
{
	/**
	 * @return ViewModel
	 */
    public function indexAction()
    {
		return new ViewModel([
		]);
    }
}
