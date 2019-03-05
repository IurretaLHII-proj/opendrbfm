<?php

namespace MA\Controller\Js;

use Zend\Mvc\MvcEvent;
use Zend\Json\Json;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use ZF\Hal\View\HalJsonModel;

class CustomerController extends \Base\Controller\Js\AbstractActionController
{
	/**
	 * @return ViewModel
	 */
    public function indexAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$collection = $em->getRepository("MA\Entity\Customer")
			->findBy(
				[],
				['created' => 'DESC']
			);

		$payload = $this->prepareHalCollection($this->getPaginator($collection), 'customer/json');

		return new HalJsonModel([
			'payload' => $payload,
		]);
	}
}
