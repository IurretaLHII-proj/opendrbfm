<?php

namespace MA\Controller\Js;

use Zend\Mvc\MvcEvent;
use Zend\Json\Json;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use ZF\Hal\View\HalJsonModel;

class MaterialController extends \Base\Controller\Js\AbstractActionController
{
	/**
	 * @return ViewModel
	 */
    public function indexAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$collection = $em->getRepository("MA\Entity\Material")->findBy([],['created' => 'DESC']);

		$paginator = $this->getPaginator($collection, count($collection));

		$payload = $this->prepareHalCollection($paginator, 'process/material/json');

		return new HalJsonModel([
			'payload' => $payload,
		]);
	}
}
